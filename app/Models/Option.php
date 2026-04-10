<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $label
 * @property string|null $image_url
 * @property string $code
 * @property int $quiz_id
 * @property int $order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Quiz $quiz
 * @property-read Collection|QuizAnswer[] $quizAnswers
 */
class Option extends Model
{
    protected $fillable = ['code','label', 'image_url', 'quiz_id', 'order'];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function quizAnswers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'option_id');
    }
}
