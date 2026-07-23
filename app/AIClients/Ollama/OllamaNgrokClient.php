<?php

namespace App\AIClients\Ollama;

use App\AI\Dto\ConversationDto;
use App\AI\Responses\LlmResponse;
use App\AIClients\Ollama\Dto\OllamaEmbeddingDto;
use App\AIClients\Ollama\Dto\OllamaResponseDto;
use App\Contracts\AIClients\ILlmClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OllamaNgrokClient implements ILlmClient
{
    const API_CHAT = '/api/chat';
    const API_EMBEDDINGS = '/api/embeddings';
    protected string $baseUrl;
    protected string $model;
    protected string $modelEmbedding;

    public function __construct() {
        $this->baseUrl = Cache::get('dynamic_ollama_base_url', rtrim(config('ai.ollama.base_url'), '/'));
        $this->model = config('ai.ollama.model');
        $this->modelEmbedding = config('ai.ollama.model_embedding');
    }

    /**
     * @throws ConnectionException
     */
    public function chat(ConversationDto $conversationDto, array $options = []): LlmResponse
    {
        $model = $options['model'] ?? $this->model;

        $payload = array_merge([
            'model' => $model,
            'messages' => $conversationDto->getMessages(),
            'stream' => false,
        ], $options);

        $url = sprintf('%s%s', $this->baseUrl, static::API_CHAT);

        // 1. Ghi log kiểm tra URL và Payload trước khi bắn request
        logger()->info("=== DEBUG OLLAMA START ===");
        logger()->info("URL Laravel đang gọi: " . $url);
        logger()->info("Payload Laravel gửi đi: ", $payload);

        // Gọi request duy nhất 1 lần bằng Laravel HTTP Client chuẩn chỉnh
        $response = $this->_sendRequest($url, $payload);
        $data = $response->json();

        // 2. Ghi log kiểm tra dữ liệu thực tế
        logger()->info("HTTP Status Code: " . $response->status());
        logger()->info("Dữ liệu thô nhận được (Body): " . $response->body());
        logger()->info("=== DEBUG OLLAMA END ===");

        // Kiểm tra xem dữ liệu JSON trả về có hợp lệ không
        if (empty($data) || !isset($data['message']['content'])) {
            throw new RuntimeException('Invalid JSON from LLM: Thất bại khi parse dữ liệu từ Ollama. Response thô: ' . $response->body());
        }

        $promptTokens = $data['prompt_eval_count'] ?? 0;
        $completionTokens = $data['eval_count'] ?? 0;

        $ollamaResponse = new OllamaResponseDto(
            model: $data['model'] ?? $model,
            content: $data['message']['content'] ?? '',
            promptTokens: $promptTokens,
            completionTokens: $completionTokens,
            totalTokens: $promptTokens + $completionTokens,
            raw: $data
        );

        return $ollamaResponse->toLlmResponse();
    }

    /**
     * @throws ConnectionException
     */
    protected function _sendRequest(string $url, array $payload): Response
    {
        // Sử dụng Http Client của Laravel, cấu hình các tùy chọn cURL sâu thông qua withOptions
        return Http::timeout(300)->withOptions([
            'curl' => [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 300,
                CURLOPT_CONNECTTIMEOUT => 15,
            ],
            'verify' => false, // Bỏ qua xác thực SSL nếu môi trường local/colab bị lỗi chứng chỉ
        ])->withHeaders([
            'Content-Type'               => 'application/json',
            'Accept'                     => 'application/json',
            'ngrok-skip-browser-warning' => 'true', // Vượt trang cảnh báo màu đỏ của Ngrok
            'User-Agent'                 => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        ])->post($url, $payload);
    }

    /**
     * @throws GuzzleException
     */
    public function chatStream(ConversationDto $conversationDto, callable $onChunk, array $options = []): void
    {
        $model = $options['model'] ?? $this->model;

        $payload = array_merge([
            'model' => $model,
            'messages' => $conversationDto->getMessages(),
            'stream' => true,
        ], $options);

        $client = new Client([
            'timeout' => 300,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'ngrok-skip-browser-warning' => 'true',
            ]
        ]);

        $response = $client->post($this->baseUrl . self::API_CHAT, [
            'json' => $payload,
            'stream' => true,
        ]);

        $body = $response->getBody();
        $buffer = '';

        while (!$body->eof()) {
            $chunk = $body->read(1024);

            if (!$chunk) {
                continue;
            }

            $buffer .= $chunk;

            while (($pos = strpos($buffer, "\n")) !== false) {
                $line = substr($buffer, 0, $pos);
                $buffer = substr($buffer, $pos + 1);

                $line = trim($line);
                if (!$line) continue;

                $data = json_decode($line, true);

                if (!$data) continue;

                if (isset($data['message']['content'])) {
                    $onChunk($data['message']['content']);
                }

                if (!empty($data['done'])) {
                    return;
                }
            }
        }
    }

    /**
     * @throws ConnectionException
     */
    public function embedding(string $text, array $options = []): OllamaEmbeddingDto
    {
        $model = $options['model'] ?? $this->modelEmbedding;

        $prompt = mb_strtolower(trim($text));
        $prompt = preg_replace('/\s+/', ' ', $prompt);

        $payload = array_merge([
            'model' => $model,
            'prompt' => $prompt,
        ], $options);

        $url = sprintf('%s%s', $this->baseUrl, static::API_EMBEDDINGS);

        $response = $this->_sendRequest($url, $payload);
        $data = $response->json();

        if (empty($data) || !isset($data['embedding'])) {
            throw new RuntimeException('Invalid JSON from Embedding: Không lấy được vector từ Ollama. Response thô: ' . $response->body());
        }

        $embedding = $data['embedding'] ?? [];

        return new OllamaEmbeddingDto(
            model: $data['model'] ?? $model,
            embedding: array_map('floatval', $embedding),
            content: $text,
            raw: $data
        );
    }
}
