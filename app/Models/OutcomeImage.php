<?php

namespace App\Models;

use App\Enums\ImageChooseEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id The UUID of the outcome image.
 * @property string $player_id The UUID of the associated player.
 * @property string $gaming_session_id The UUID of the associated gaming session.
 * @property ImageChooseEnum $player_choose_image
 * @property Carbon $share_facebook_at
 * @property string|null $image_1 The path to the first outcome image.
 * @property string|null $image_2 The path to the second outcome image.
 * @property string|null $image_has_frame The path to the third outcome image.
 * @property Carbon $created_at The timestamp when the outcome image was created.
 * @property Carbon $updated_at The timestamp when the outcome image was last updated.
 */
class OutcomeImage extends Model
{
    protected $fillable = [
        'player_id',
        'gaming_session_id',
        'player_choose_image',
        'share_facebook_at',
        'image_1',
        'image_2',
        'image_has_frame'
    ];

    public static function make(
        string $playerId, string $gamingSessionId, ?string $image1 = null, ?string $image2 = null, ?string $image3 = null, ?string $image4 = null
    ): static
    {
        return new static([
            'player_id' => $playerId,
            'gaming_session_id' => $gamingSessionId,
            'image_1' => $image1,
            'image_2' => $image2,
            'image_3' => $image3,
            'image_4' => $image4,
        ]);
    }

    protected $casts = [
        'share_facebook_at' => 'datetime',
        'player_choose_image' => ImageChooseEnum::class,
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
