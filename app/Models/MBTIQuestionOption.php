<?php

namespace App\Models;

use App\Enums\MBTIDimensionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $question_id
 * @property string $content
 * @property string $score_side
 * @property int $score_value
 */
class MBTIQuestionOption extends Model
{
    use HasFactory;

    protected $table = 'mbti_question_options';

    protected $fillable = [
        'question_id',
        'content',
        'score_side',
        'score_value'
    ];

    protected $casts = [
        'score_side' => MBTIDimensionEnum::class,
        'score_value' => 'integer',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(MBTIQuestion::class, 'question_id', 'id');
    }
}
