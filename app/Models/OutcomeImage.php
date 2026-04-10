<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id The UUID of the outcome image.
 * @property string $player_id The UUID of the associated player.
 * @property string $gaming_session_id The UUID of the associated gaming session.
 * @property Carbon $share_fb_at
 * @property Carbon $share_ig_at
 * @property Carbon $save_at
 * @property string|null $image The path to the first outcome image.
 * @property string|null $image_has_frame The path to the second outcome image.
 * @property Carbon $created_at The timestamp when the outcome image was created.
 * @property Carbon $updated_at The timestamp when the outcome image was last updated.
 */
class OutcomeImage extends Model
{
    protected $fillable = [
        'player_id',
        'gaming_session_id',
        'player_choose_image',
        'share_fb_at',
        'share_ig_at',
        'save_at',
        'image',
        'image_has_frame',
    ];

    public static function make(string $playerId, string $gamingSessionId): static
    {
        return new static([
            'player_id' => $playerId,
            'gaming_session_id' => $gamingSessionId,
        ]);
    }

    protected $casts = [
        'share_fb_at' => 'datetime',
        'share_ig_at' => 'datetime',
        'save_at' => 'datetime',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function gamingSession(): BelongsTo
    {
        return $this->belongsTo(GamingSession::class);
    }
}
