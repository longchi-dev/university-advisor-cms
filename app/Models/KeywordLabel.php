<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id The auto-incrementing ID of the keyword label.
 * @property int $keyword_id The ID of the associated keyword.
 * @property string $label The label text.
 * @property Carbon $created_at The timestamp when the label was created.
 * @property Carbon $updated_at The timestamp when the label was last updated.
 */
class KeywordLabel extends Model
{
    protected $fillable = [
        'label',
        'keyword_id',
    ];

    public static function make(string $label, int $keywordId): static
    {
        return new static([
            'label' => $label,
            'keyword_id' => $keywordId,
        ]);
    }

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(Keyword::class);
    }

    public function themes(): HasMany
    {
        return $this->hasMany(Theme::class);
    }
}
