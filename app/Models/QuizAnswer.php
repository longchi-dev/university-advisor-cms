<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $player_id
 * @property int $quiz_id
 * @property int $option_id
 * @property string $question_snapshot
 * @property string $label_snapshot
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Player $player
 * @property-read Quiz $quiz
 * @property-read Option $option
 */
class QuizAnswer extends Model
{
    protected $fillable = ['player_id', 'quiz_id', 'option_id', 'question_snapshot', 'label_snapshot'];

    public static function make(
        string $babyId, int $quizId, int $optionId, string $questionSnapshot, string $labelSnapshot
    ): static
    {
        return new static([
            'player_id' => $babyId,
            'quiz_id' => $quizId,
            'option_id' => $optionId,
            'question_snapshot' => $questionSnapshot,
            'label_snapshot' => $labelSnapshot,
        ]);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'option_id');
    }
}
