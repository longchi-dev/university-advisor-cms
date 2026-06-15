<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $question
 * @property string $dimension
 */
class MBTIQuestion extends Model
{
    use HasFactory;

    protected $table = 'mbti_questions';

    protected $fillable = [
        'question',
        'dimension'
    ];

    public function options(): HasMany
    {
        return $this->hasMany(MBTIQuestionOption::class, 'question_id', 'id');
    }
}
