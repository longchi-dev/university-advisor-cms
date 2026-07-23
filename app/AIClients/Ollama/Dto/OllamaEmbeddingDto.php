<?php

namespace App\AIClients\Ollama\Dto;

readonly class OllamaEmbeddingDto
{
    public function __construct(
        public string $model,
        public ?array $embedding = null,
        public ?string $content = null,
        public ?array $raw = null,
    ) { }
}
