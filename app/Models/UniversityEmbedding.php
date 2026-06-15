<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $score_id
 * @property string $content
 * @property string|null $searchable_text
 * @property string $embedding
 * @property string|null $model
 */
class UniversityEmbedding extends Model
{
    protected $table = 'university_embeddings';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'score_id',
        'content',
        'searchable_text',
        'embedding',
        'model',
    ];

    public function score(): BelongsTo
    {
        return $this->belongsTo(Score::class);
    }
}
