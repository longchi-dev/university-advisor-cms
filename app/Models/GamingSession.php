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
 * @property int $theme_keyword_id
 * @property Carbon|null $finished_at The timestamp when the session finished.
 * @property string|null $full_url The full URL associated with the session.
 * @property string|null $ip_address The IP address of the client.
 * @property string|null $browser The browser information of the client.
 * @property int $ref_gaming_session_id
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
        'theme_keyword_id',
        'finished_at',
        'full_url',
        'ip_address',
        'browser',
        'ref_gaming_session_id',
    ];

    protected $casts = [
        'finished_at' => 'datetime',
    ];

    public static function make(
        string $playerId,
        string $imageId,
        int $themeKeywordId,
        string $fullUrl,
        ?string $ipAddress = null,
        ?string $browser = null,
        ?string $refGamingSessionId = null,
        ?\DateTimeInterface $finishedAt = null,
    ): static
    {
        return new static([
            'player_id' => $playerId,
            'image_id' => $imageId,
            'theme_keyword_id' => $themeKeywordId,
            'full_url' => $fullUrl,
            'finished_at' => $finishedAt,
            'ip_address' => $ipAddress,
            'browser' => $browser,
            'ref_gaming_session_id' => $refGamingSessionId
        ]);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function uploadImage(): BelongsTo
    {
        return $this->belongsTo(UploadImage::class, 'image_id');
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
