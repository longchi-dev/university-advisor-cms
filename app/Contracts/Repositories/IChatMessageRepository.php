<?php

namespace App\Contracts\Repositories;

use App\Models\AIConversation\ChatMessage;
use Illuminate\Support\Collection;

interface IChatMessageRepository
{
    public function save(ChatMessage $chatMessage);

    public function findById(string $chatMessageId): ?ChatMessage;

    /**
     * @return Collection<int, ChatMessage>
     */
    public function findByChatSessionId(string $chatSessionId): Collection;

    public function findAssistantByRefId(string $messageRefId): ?ChatMessage;
    public function getRecentHistory(string $chatSessionId, int $limit = 5, ?string $currentMessageId = null): Collection;
}
