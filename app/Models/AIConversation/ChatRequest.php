<?php

namespace App\Models\AIConversation;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $user_id
 * @property string $request_id
 * @property string $provider
 * @property string $model
 * @property string|null $context
 * @property string $prompt
 * @property string $response_text
 * @property float|null $tokens_input
 * @property float|null $tokens_output
 * @property float|null $tokens_total
 * @property float|null $latency_ms
 * @property \DateTimeInterface $logged_at
 * @property \DateTimeInterface|null $created_at
 * @property \DateTimeInterface|null $updated_at
 */
class ChatRequest extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $table = 'log_ai_tokens_usaged';

    protected $fillable = [
        'user_id',
        'request_id',
        'provider',
        'model',
        'context',
        'prompt',
        'response_text',
        'tokens_input',
        'tokens_output',
        'tokens_total',
        'latency_ms',
        'logged_at'
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public static function make(
        ?int $userId,
        string $requestId,
        string $provider,
        string $model,
        ?string $context,
        string $prompt,
        string $responseText,
        ?float $tokensInput,
        ?float $tokensOutput,
        ?float $tokensTotal,
        ?float $latencyMs,
        \DateTimeInterface $loggedAt,
    ): static {
        return new static([
            'user_id' => $userId,
            'request_id' => $requestId,
            'provider' => $provider,
            'model' => $model,
            'context' => $context,
            'prompt' => $prompt,
            'response_text' => $responseText,
            'tokens_input' => $tokensInput,
            'tokens_output' => $tokensOutput,
            'tokens_total' => $tokensTotal,
            'latency_ms' => $latencyMs,
            'logged_at' => $loggedAt
        ]);
    }
}
