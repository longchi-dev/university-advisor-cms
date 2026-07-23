<?php

namespace App\AI\Responses;

use App\AIClients\Ollama\Dto\OllamaResponseDto;
use App\Enums\UserIntentEnum;

class LlmValidateResultResponse
{
    public function __construct(
        protected UserIntentEnum $intent,
        protected string $language,
        protected string $translated,
        protected string $llmMessage,
        protected string $llmMessageLocalized,
        protected ?array $raw = null,
    ) {}

    public function getIntent(): UserIntentEnum
    {
        return $this->intent;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getTranslated(): string
    {
        return $this->translated;
    }

    public function getLlmMessage(): string
    {
        return $this->llmMessage;
    }

    public function getLlmMessageLocalized(): string
    {
        return $this->llmMessageLocalized;
    }

    public function getRaw(): ?array
    {
        return $this->raw;
    }

    public static function fromArray(array $data): self
    {
        $intent = strtolower(trim($data['user_intent'] ?? ''));
        return new self(
            UserIntentEnum::tryFrom($intent) ?? UserIntentEnum::IRRELEVANT,
            $data['language'] ?? 'unknown',
            $data['translated'] ?? '',
            $data['message'] ?? '',
            $data['message_localized'] ?? '',
            $data
        );
    }

    public static function fromOllamaResponse(OllamaResponseDto $response): self
    {
        $content = $response->content ?? '{}';

        $data = json_decode($content, true);

        if (!is_array($data)) {
            $data = [];
        }

        return self::fromArray($data);
    }
}
