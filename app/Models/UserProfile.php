<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $user_id
 * @property string|null $mbti_type
 * @property array|null $favorite_subjects
 * @property array|null $weak_subjects
 * @property string|null $career_goal
 * @property string|null $target_major
 * @property string|null $work_style
 * @property float|null $score
 */
class UserProfile extends Model
{
    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'mbti_type',
        'favorite_subjects',
        'weak_subjects',
        'career_goal',
        'target_major',
        'work_style',
        'score'
    ];

    protected $casts = [
        'favorite_subjects' => 'array',
        'weak_subjects' => 'array',
        'score' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
