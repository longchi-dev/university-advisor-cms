<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromptRandom extends Model
{
    protected $table = 'prompt_randoms';

    protected $fillable = [
        'group',
        'value',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'weight' => 'integer',
    ];
}
