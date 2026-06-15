<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $school_id
 * @property int $major_id
 * @property int $year
 * @property float $score
 * @property string|null $block
 * @property string|null $level
 * @property string|null $note
 * @property string|null $source_url
 */
class Score extends Model
{
    protected $table = 'scores';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'school_id',
        'major_id',
        'year',
        'score',
        'block',
        'level',
        'note',
        'source_url',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function embedding(): HasOne
    {
        return $this->hasOne(UniversityEmbedding::class);
    }
}
