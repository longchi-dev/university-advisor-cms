<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $question
 * @property bool $is_multiple_choice
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|Option[] $options
 * @property-read Collection|QuizAnswer[] $quizAnswers
 */
class Quiz extends Model
{
    protected $fillable = ['question', 'is_multiple_choice'];

    protected $casts = [
        'is_multiple_choice' => 'boolean',
    ];

    public static function make(string $question, bool $isMultipleChoice = true): static
    {
        return new static([
            'question' => $question,
            'is_multiple_choice' => $isMultipleChoice,
        ]);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class, 'quiz_id');
    }

    public function quizAnswers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_id');
    }
}
