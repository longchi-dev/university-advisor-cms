<?php

namespace App\Models;

use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $job_id
 * @property int $from_year
 * @property int $to_year
 * @property JobStatus $status
 * @property int $total_records
 * @property string|null $output_file
 * @property string|null $log
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class CrawlJob extends Model
{
    protected $fillable = [
        'job_id',
        'from_year',
        'to_year',
        'status',
        'total_records',
        'output_file',
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
