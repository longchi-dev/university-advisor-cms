<?php

namespace App\Models\AIConversation;

use App\Enums\ChatIntentEnum;
use App\Models\User;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id
 * @property string $user_id
 * @property ChatIntentEnum $chat_intent
 * @property \DateTimeInterface|null $started_at
 * @property \DateTimeInterface|null $finished_at
 */
class ChatSession extends Model
{
    use Uuid;

    protected $table = 'ai_chat_sessions';

    protected $fillable = [
        'user_id',
        'chat_intent',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'chat_intent' => ChatIntentEnum::class,
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public static function make(string $userId): static
    {
        return new static([
            'user_id' => $userId,
            'chat_intent' => ChatIntentEnum::ADVISE,
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
