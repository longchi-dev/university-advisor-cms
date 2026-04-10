<?php

namespace App\Models;

use App\Exceptions\ImageInvalidException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @property int $uuid
 * @property string $player_id
 * @property string $disk
 * @property string $path
 * @property bool $is_valid
 * @property bool $terms_of_use
 * @property Carbon $confirmed_terms_at
 * @property string $exception_message
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
        'is_valid',
        'exception_message',
    ];

    public $casts = [
        'is_valid' => 'bool',
    ];

    public static function make(string $playerId): static
    {
        return new static([
            'player_id' => $playerId,
        ]);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function validPlayerImage(string $playerId): void
    {
        if (!$this?->is_valid) {
            throw new ImageInvalidException();
        }

        if ($this->player_id !== $playerId) {
            throw new AccessDeniedHttpException();
        }
    }
}
