<?php

namespace App\Models;

use App\Enums\FrameEnum;
use App\Exceptions\SessionAlreadyFinishedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property string $uuid The UUID of the gaming session.
 * @property string $player_id The UUID of the associated player.
 * @property string $image_id The UUID of the associated image.
 * @property Carbon|null $finished_at The timestamp when the session finished.
 * @property string|null $full_url The full URL associated with the session.
 * @property string|null $ip_address The IP address of the client.
 * @property string|null $browser The browser information of the client.
 * @property FrameEnum|null $frame The browser information of the client.
 * @property array $keywords The JSON array of keywords.
 * @property Carbon $created_at The timestamp when the session was created.
 * @property Carbon $updated_at The timestamp when the session was last updated.
 */
class GamingSession extends Model
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'player_id',
        'image_id',
        'finished_at',
        'full_url',
        'ip_address',
        'browser',
        'keywords',
        'frame',
        'ref_gaming_session_id'
    ];

    protected $casts = [
        'finished_at' => 'datetime',
        'keywords' => 'array',
        'frame' => FrameEnum::class,
    ];

    public static function make(
        string $playerId,
        string $imageId,
        string $fullUrl,
        ?array $keywords,
        ?string $ipAddress = null,
        ?string $browser = null,
        ?\DateTimeInterface $finishedAt = null,
    ): static
    {
        return new static([
            'player_id' => $playerId,
            'image_id' => $imageId,
            'full_url' => $fullUrl,
            'keywords' => $keywords,
            'finished_at' => $finishedAt,
            'ip_address' => $ipAddress,
            'browser' => $browser,
        ]);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function outcomeImage(): HasOne
    {
        return $this->hasOne(OutcomeImage::class, 'gaming_session_id');
    }

    public function finish(): void
    {
        if ($this->finished_at) {
            throw new SessionAlreadyFinishedException();
        }
        $this->finished_at = now();
    }
}
