<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HubspotLog extends Model
{
    protected $table = 'api_request_logs';

    protected $fillable = [
        'action',
        'endpoint',
        'error_message',
        'is_success',
        'request_payload',
        'requested_at',
        'responded_at',
        'response_payload',
        'service',
        'status_code',
    ];

    protected $casts = [
        'is_success' => 'boolean',
        'request_payload' => 'array',
        'response_payload' => 'array',
        'requested_at' => 'datetime',
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
