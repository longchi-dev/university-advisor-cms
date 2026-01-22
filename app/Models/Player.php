<?php

namespace App\Models;

use App\Enums\ImageChooseEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string $uuid The UUID of the player.
 * @property string $full_url The UUID of the player.
 * @property string|null $name The name of the player.
 * @property bool $is_banned Indicates if the player is banned.
 * @property Carbon $created_at The timestamp when the player was created.
 * @property Carbon $updated_at The timestamp when the player was last updated.
 */
class Player extends Model
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'full_url',
        'name',
        'is_banned',
    ];

    protected $casts = [
        'is_banned' => 'boolean',
    ];

    public static function make(
        string $fullUrl, ?ImageChooseEnum $playerChooseImage = null, ?string $name = null, ?\DateTimeInterface $shareFinishAt = null
    ): static
    {
        return new static([
            'full_url' => $fullUrl,
            'player_choose_image' => $playerChooseImage,
            'share_facebook_at' => $shareFinishAt,
            'name' => $name,
        ]);
    }

    public function uploadImage(): HasOne
    {
        return $this->hasOne(UploadImage::class);
    }

    public function gamingSessions(): HasMany
    {
        return $this->hasMany(GamingSession::class);
    }

    public function outcomeImages(): HasMany
    {
        return $this->hasMany(OutcomeImage::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
