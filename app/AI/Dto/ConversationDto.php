<?php

namespace App\AI\Dto;

use Illuminate\Support\Str;

class ConversationDto
{
    protected ?string $alias;
    protected array $messages = [];
    protected ?string $systemPrompt = null;

    public function __construct(?string $alias = null) {
        $this->alias = $alias ?? (string) Str::uuid();
    }

    public function pushUserMessage(string $content): self
    {
        $this->messages[] = [
            'role' => 'user',
            'content' => $content
        ];

        return $this;
    }

    public function pushAssistantMessage(string $content): self
    {
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $content
        ];

        return $this;
    }

    public function setSystemPrompt(string $prompt): self
    {
        $this->systemPrompt = $prompt;
        return $this;
    }

    public function getMessages(): array
    {
        $messages = $this->messages;

        if ($this->systemPrompt) {
            array_unshift($messages, [
                'role' => 'system',
                'content' => $this->systemPrompt
            ]);
        }

        return $messages;
    }

    public function toArray(): array
    {
        return [
            'messages' => $this->getMessages()
        ];
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function getUserPrompt(): string
    {
        $prompts = [];

        foreach ($this->messages as $message) {
            if ($message['role'] === 'user') {
                $prompts[] = $message['content'];
            }
        }

        return implode("\n", $prompts);
    }
}
