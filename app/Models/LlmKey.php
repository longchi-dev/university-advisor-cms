<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $name
 * @property string $api_key
 * @property bool $is_active
 * @property int $used_tokens
 * @property int $quota_limit
 * @property Carbon $last_used_at
 */
class LlmKey extends Model
{
    protected $fillable = [
        'name',
        'api_key',
        'is_active',
        'used_tokens',
        'quota_limit',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];
}
