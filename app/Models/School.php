<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $website
 */
class School extends Model
{
    protected $table = 'schools';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'website',
    ];

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($school) {
            $slug = Str::slug($school->name);

            $count = self::query()->where('slug', 'like', "{$slug}%")->count();
            $school->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }
}
