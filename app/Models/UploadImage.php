<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $uuid
 * @property string $player_id
 * @property string $disk
 * @property string $path
 * @property bool $status
 * @property string $prompt
 * @property string $exception_message
 * @property bool $terms_of_use
 * @property \DateTimeInterface $confirmed_terms_at
 * @property Carbon $created_at The timestamp when the image was created.
 * @property Carbon $updated_at The timestamp when the image was last updated.
 */
class UploadImage extends Model
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'player_id',
        'disk',
        'path',
        'status',
        'prompt',
        'exception_message',
        'terms_of_use',
        'confirmed_terms_at'
    ];

    public $casts = [
        'confirmed_terms_at' => 'datetime',
        'terms_of_use' => 'bool',
        'status' => 'bool'
    ];

    public static function make(string $playerId, ?string $prompt = null): static
    {
        return new static([
            'player_id' => $playerId,
            'prompt' => $prompt,
        ]);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
