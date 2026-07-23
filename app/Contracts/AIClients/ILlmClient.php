<?php

namespace App\Contracts\AIClients;

use App\AI\Dto\ConversationDto;
use App\AI\Responses\LlmResponse;
use App\AIClients\Ollama\Dto\OllamaEmbeddingDto;

interface ILlmClient
{
    public function chat(ConversationDto $conversationDto, array $options = []): LlmResponse;
    public function chatStream(ConversationDto $conversationDto, callable $onChunk, array $options = []): void;
    public function embedding(string $text, array $options = []): OllamaEmbeddingDto;
}
