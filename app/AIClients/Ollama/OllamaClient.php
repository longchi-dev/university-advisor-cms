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

class OllamaClient implements ILlmClient
{
    const API_CHAT = '/api/chat';
    const API_EMBEDDINGS = '/api/embeddings';
    protected string $baseUrl;
    protected string $model;
    protected string $modelEmbedding;

    public function __construct() {
        $this->baseUrl = Cache::get('dynamic_ollama_base_url', config('ai.ollama.base_url'));
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

        $response = $this->_sendRequest($url, $payload);
        $data = $response->json();

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
        return Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
            ->timeout(120)
            ->post($url, $payload);
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
            'timeout' => 120,
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

            // Ollama trả NDJSON → split theo newline
            while (($pos = strpos($buffer, "\n")) !== false) {
                $line = substr($buffer, 0, $pos);
                $buffer = substr($buffer, $pos + 1);

                $line = trim($line);
                if (!$line) continue;

                $data = json_decode($line, true);

                if (!$data) continue;

                // lấy content từng chunk
                if (isset($data['message']['content'])) {
                    $onChunk($data['message']['content']);
                }

                // kết thúc stream
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

        $embedding = $data['embedding'] ?? [];

        return new OllamaEmbeddingDto(
            model: $data['model'] ?? $model,
            embedding: array_map('floatval', $embedding),
            content: $text,
            raw: $data
        );
    }
}
