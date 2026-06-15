<?php

namespace App\Models\AI;

use App\Enums\AIPromptTemplateEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property AiPromptTemplateEnum $type
 * @property string $prompt
 */
class AIPromptTemplate extends Model
{
    protected $table = 'ai_prompt_templates';
    protected $fillable = [
        'type',
        'prompt'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'type' => AiPromptTemplateEnum::class
    ];

    public static function make(AiPromptTemplateEnum $type, string $prompt): static
    {
        return new static([
            'type' => $type,
            'prompt' => $prompt
        ]);
    }
}
