<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LlmLog extends Model
{
    use HasFactory;

    protected $table = 'llm_logs';

    protected $fillable = [
        'llm_name',
        'llm_model',
        'prompt',
        'image_url',
        'response',
        'request_at',
        'response_at',
        'exec_time',
    ];

    // Ép kiểu cho các field
    protected $casts = [
        'request_at' => 'datetime',
        'response_at' => 'datetime',
        'exec_time'   => 'float',
    ];
}
