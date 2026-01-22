<?php

namespace App\Services;

use App\Models\LlmKey;

class GeminiKeyService
{
    public static function getAvailableKey(): ?LlmKey
    {
        return LlmKey::query()
            ->where('is_active', true)
            ->orderBy('last_used_at') // round robin
            ->first();
    }

    public static function logUsage(LlmKey $key, int $tokens): void
    {
        $key->increment('used_tokens', $tokens);
        $key->update(['last_used_at' => now()]);

        // nếu có quota_limit thì disable key khi hết
        if ($key->quota_limit && $key->used_tokens >= $key->quota_limit) {
            $key->update(['is_active' => false]);
        }
    }
}
