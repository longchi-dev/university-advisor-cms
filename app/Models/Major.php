<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 */
class Major extends Model
{
    protected $table = 'majors';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($major) {
            $slug = Str::slug($major->name);

            $count = self::query()->where('slug', 'like', "{$slug}%")->count();
            $major->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }
}
