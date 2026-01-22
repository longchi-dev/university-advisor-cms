<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id The auto-incrementing ID of the keyword.
 * @property string $type The type of the keyword.
 * @property bool $is_active Indicates if the keyword is active.
 * @property Carbon $created_at The timestamp when the keyword was created.
 * @property Carbon $updated_at The timestamp when the keyword was last updated.
 */
class Keyword extends Model
{
    protected $fillable = [
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function make(string $type): static
    {
        return new static([
            'type' => $type
        ]);
    }

    public function labels(): HasMany
    {
        return $this->hasMany(KeywordLabel::class);
    }
}
