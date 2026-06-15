<?php

namespace App\Models\AIConversation;

use App\Enums\ChatMessageStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\UserIntentEnum;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $chat_session_id
 * @property RoleEnum $role
 * @property array $metadata
 * @property string $message
 * @property string|null $message_ref_id
 * @property string|null $error_message
 * @property string $status
 * @property Carbon $created_at
 */
class ChatMessage extends Model
{
    use Uuid, SoftDeletes;

    protected $table = 'ai_chat_messages';

    protected $fillable = [
        'chat_session_id',
        'role',
        'metadata',
        'message',
        'message_ref_id',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'role' => RoleEnum::class,
            'metadata' => 'array',
            'status' => ChatMessageStatusEnum::class,
            'created_at' => 'datetime'
        ];
    }

    public function setUserIntent(UserIntentEnum $userIntent): void
    {
        if ($this->role == RoleEnum::USER) {
            $this->metadata = array_merge(
                $this->metadata,
                ['user_intent' => $userIntent->value]
            );
        }
    }

    public function getUserIntent(): ?UserIntentEnum
    {
        if ($this->role == RoleEnum::USER) {
            return UserIntentEnum::tryFrom($this->metadata['user_intent'] ?? '');
        }

        return null;
    }

    public function isUserIntentValid(): bool
    {
        return ! in_array($this->getUserIntent(), [UserIntentEnum::BLOCKED, UserIntentEnum::IRRELEVANT]);
    }

    public static function make(
        string $chatSessionId,
        RoleEnum $role,
        string $message,
        ?string $messageRefId = null,
        array $metadata = []
    ): static {
        return new static([
            'chat_session_id' => $chatSessionId,
            'role' => $role,
            'message' => $message,
            'message_ref_id' => $messageRefId,
            'metadata' => $metadata
        ]);
    }

    public static function makeByUser(
        string $chatSessionId,
        string $message,
        array $metadata = []
    ): static {
        return static::make(
            chatSessionId: $chatSessionId,
            role: RoleEnum::USER,
            message: $message,
            metadata: $metadata
        );
    }

    public static function makeByAssistant(
        string $chatSessionId,
        string $message,
        string $translatedMessage,
        string $messageRefId,
        array $metadata = []
    ): static {
        $metadata = array_merge($metadata, [
            'translated' => $translatedMessage
        ]);

        return static::make(
            chatSessionId: $chatSessionId,
            role: RoleEnum::ASSISTANT,
            message: $message,
            messageRefId: $messageRefId,
            metadata: $metadata
        );
    }

    public function chatSession(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id', 'id');
    }
}
