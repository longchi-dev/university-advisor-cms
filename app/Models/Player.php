<?php

namespace App\Models;

use App\Enums\ImageChooseEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string $uuid
 * @property string $last_name
 * @property string $first_name
 * @property string $email
 * @property string $phone
 * @property string $full_url
 * @property bool $is_banned
 * @property array $terms_of_use
 * @property Carbon $confirmed_terms_at
 * @property Carbon $last_return_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Player extends Model
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'phone',
        'full_url',
        'is_banned',
        'confirmed_terms_at',
        'last_return_at',
        'terms_of_use',
    ];

    protected $casts = [
        'is_banned' => 'boolean',
        'confirmed_terms_at' => 'datetime',
        'last_return_at' => 'datetime',
        'terms_of_use' => 'array'
    ];

    public static function make(
        string $lastName, string $firstName, string $email, string $phone, string $fullUrl, array $termsOfUse
    ): static
    {
        return new static([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'email' => $email,
            'phone' => $phone,
            'full_url' => $fullUrl,
            'terms_of_use' => $termsOfUse
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

    public function quizAnswers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'player_id', 'uuid');
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
