<?php

namespace App\Models\AI;

use App\Enums\AILogStatusEnum;
use App\Enums\AIPromptTemplateEnum;
use App\Models\AIConversation\ChatMessage;
use App\Models\AIConversation\ChatSession;
use App\Models\User;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string|null $user_id
 * @property string|null $chat_session_id
 * @property string|null $chat_message_id
 * @property string|null $model
 * @property AIPromptTemplateEnum $prompt_type
 * @property AILogStatusEnum $status
 * @property string $prompt
 * @property string|null $response
 * @property array|null $metadata
 * @property int|null $tokens_input
 * @property int|null $tokens_output
 * @property int|null $tokens_total
 * @property float|null $execution_time_ms
 * @property string|null $error_message
 * @property Carbon|null $logged_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class AILogPrompt extends Model
{
    use Uuid;

    protected $table = 'ai_log_prompts';

    protected $fillable = [
        'user_id',
        'chat_session_id',
        'chat_message_id',
        'model',
        'prompt_type',
        'prompt',
        'response',
        'metadata',
        'tokens_input',
        'tokens_output',
        'tokens_total',
        'execution_time_ms',
        'status',
        'error_message',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'prompt_type' => AIPromptTemplateEnum::class,
            'status' => AILogStatusEnum::class,
            'metadata' => 'array',
            'tokens_input' => 'integer',
            'tokens_output' => 'integer',
            'tokens_total' => 'integer',
            'execution_time_ms' => 'float',
            'logged_at' => 'datetime',
        ];
    }

    public static function make(
        AIPromptTemplateEnum $promptType,
        string $prompt,
        ?string $response = null,
        ?string $model = null,
        ?string $userId = null,
        ?string $chatSessionId = null,
        ?string $chatMessageId = null,
        ?array $metadata = [],
        ?int $tokensInput = null,
        ?int $tokensOutput = null,
        ?int $tokensTotal = null,
        ?float $executionTimeMs = null,
        AILogStatusEnum $status = AILogStatusEnum::PENDING,
        ?string $errorMessage = null,
        ?Carbon $loggedAt = null,
    ): static {
        return new static([
            'user_id' => $userId,
            'chat_session_id' => $chatSessionId,
            'chat_message_id' => $chatMessageId,
            'model' => $model,
            'prompt_type' => $promptType,
            'prompt' => $prompt,
            'response' => $response,
            'metadata' => $metadata,
            'tokens_input' => $tokensInput,
            'tokens_output' => $tokensOutput,
            'tokens_total' => $tokensTotal,
            'execution_time_ms' => $executionTimeMs,
            'status' => $status,
            'error_message' => $errorMessage,
            'logged_at' => $loggedAt ?? now(),
        ]);
    }

    public function chatMessage(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'chat_message_id', 'id');
    }

    public function chatSession(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function markSuccess(
        string $response,
        ?int $tokensInput = null,
        ?int $tokensOutput = null,
        ?int $tokensTotal = null,
        ?float $executionTimeMs = null,
        ?array $metadata = null
    ): void {
        $this->update([
            'response' => $response,
            'status' => AILogStatusEnum::SUCCESS,
            'tokens_input' => $tokensInput,
            'tokens_output' => $tokensOutput,
            'tokens_total' => $tokensTotal,
            'execution_time_ms' => $executionTimeMs,
            'metadata' => $metadata ?? $this->metadata,
        ]);
    }

    public function markError(
        \Throwable $e,
        ?float $executionTimeMs = null
    ): void {
        $this->update([
            'status' => AILogStatusEnum::ERROR,
            'error_message' => $e->getMessage(),
            'execution_time_ms' => $executionTimeMs,
        ]);
    }
}
