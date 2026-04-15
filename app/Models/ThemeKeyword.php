<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $theme_id
 * @property string $icon_url
 * @property string $frame_url
 * @property string $content
 * @property string $code
 * @property int $order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ThemeKeyword extends Model
{
    protected $fillable = [
        'theme_id',
        'code',
        'icon_url',
        'frame_url',
        'content',
        'order',
    ];

    public function keywords(): HasMany
    {
        return $this->hasMany(ThemeKeyword::class);
    }
}
