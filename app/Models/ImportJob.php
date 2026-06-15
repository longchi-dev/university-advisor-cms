<?php

namespace App\Models;

use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $job_id
 * @property string|null $type
 * @property int $total
 * @property string $status
 * @property int $processed
 * @property string|null $file_path
 * @property string|null $error
 */
class ImportJob extends Model
{
    protected $table = 'import_jobs';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'job_id',
        'type',
        'total',
        'processed',
        'status',
        'file_path',
        'error',
    ];

    protected $casts = [
        'status' => JobStatus::class,
    ];
}
