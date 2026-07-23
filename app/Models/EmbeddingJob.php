<?php

namespace App\Models;

use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Model;

class EmbeddingJob extends Model
{
    protected $fillable = [
        'job_id',
        'status',
        'total',
        'processed',
        'log',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'status' => JobStatus::class,
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
