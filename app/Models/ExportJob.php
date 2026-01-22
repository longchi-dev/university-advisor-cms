<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $job_id
 * @property string $type
 * @property int $total
 * @property string $status
 * @property int $processed
 * @property string $file_path
 */
class ExportJob extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'job_id',
        'type',
        'total',
        'status',
        'processed',
        'file_path'
    ];
}
