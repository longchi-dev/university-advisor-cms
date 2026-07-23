<?php

namespace App\AI\Responses;

use App\Enums\UserIntentEnum;
use Illuminate\Support\Facades\Log;

readonly class LlmResponse
{
    public function __construct(
        public string $content,
        public ?string $model = null,
        public array $meta = [],
    ) {}

    public function text(): string
    {
        return trim($this->content);
    }

//    public function getJson(): array
//    {
//        $cleaned = trim($this->content);
//
//        $cleaned = preg_replace('/^```(json)?/i', '', $cleaned);
//        $cleaned = preg_replace('/```$/', '', $cleaned);
//
//        $decoded = json_decode(trim($cleaned), true);
//
//        if (!is_array($decoded)) {
//            throw new \RuntimeException('Invalid JSON from LLM');
//        }
//
//        return $decoded;
//    }

    public function getJson(): array
    {
        $content = trim($this->content);

        if (blank($content)) {

            Log::error('LLM returned empty response');

            throw new \RuntimeException(
                'LLM returned empty response'
            );
        }

        if (
            preg_match('/(\{.*\}|\[.*\])/s', $content, $matches)
        ) {
            $cleaned = trim($matches[0]);
        } else {
            $cleaned = $content;
        }

        $decoded = json_decode($cleaned, true);

        if (json_last_error() !== JSON_ERROR_NONE) {

            Log::error('LLM JSON Decode Failed', [
                'content' => $this->content,
                'json_error' => json_last_error_msg(),
            ]);

            throw new \RuntimeException(
                'Invalid JSON from LLM: '
                . substr($this->content, 0, 100)
            );
        }

        return $decoded;
    }

    public function getIntent(): ?UserIntentEnum
    {
        $data = $this->getJson();
        return UserIntentEnum::tryFrom($data['intent'] ?? '');
    }
}
