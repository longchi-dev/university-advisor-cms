<?php

namespace App\Contracts\Repositories;

use App\Models\AIConversation\ChatSession;
use Illuminate\Database\Eloquent\Collection;

interface IChatSessionRepository
{
    public function save(ChatSession $chatSession): void;
    public function findById(string $id): ?ChatSession;
    public function findByUserId(string $userId): Collection;
    public function findByShareToken(string $shareToken): ?ChatSession;
    public function delete(ChatSession $chatSession): bool;
}
