<?php

namespace App\AIClients\Ollama\Dto;

use App\AI\Responses\LlmResponse;

readonly class OllamaResponseDto
{
    public function __construct(
        public string $model,
        public ?string $content = null,
        public ?int $promptTokens = null,
        public ?int $completionTokens = null,
        public ?int $totalTokens = null,
        public ?array $raw = null,
        public ?\Throwable $exception = null,
    ) {}

    public function text(): string
    {
        return $this->content ?? '';
    }

    public function json(): array
    {
        if (!$this->content) {
            return [];
        }

        $normalized = $this->normalizeJsonString($this->content);

        $decoded = json_decode($normalized, true);

        if (!is_array($decoded)) {
            return [];
        }

        return $decoded;
    }

    public function ndjson(): array
    {
        if (!$this->content) {
            return [];
        }

        $content = $this->normalizeJsonString($this->content);

        $lines = array_filter(array_map('trim', explode(PHP_EOL, $content)));

        $result = [];

        foreach ($lines as $line) {
            $decoded = json_decode($line, true);
            if (is_array($decoded)) {
                $result[] = $decoded;
            }
        }

        return $result;
    }

    private function normalizeJsonString(string $content): string
    {
        $normalized = trim($content);

        if (preg_match('/```(?:json)?(.*?)```/is', $normalized, $matches)) {
            $normalized = trim($matches[1]);
        }

        return trim($normalized, "\xEF\xBB\xBF \t\n\r\0\x0B");
    }

    public function toLlmResponse(): LlmResponse
    {
        return new LlmResponse(
            content: $this->content ?? '',
            meta: [
                'model' => $this->model,
                'prompt_tokens' => $this->promptTokens,
                'completion_tokens' => $this->completionTokens,
                'total_tokens' => $this->totalTokens,
                'raw' => $this->raw,
                'exception' => $this->exception?->getMessage(),
            ]
        );
    }
}
