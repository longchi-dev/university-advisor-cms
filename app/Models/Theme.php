<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $label
 * @property string $icon_url
 * @property int $order
 * @@property Carbon $created_at
 * @@property Carbon $updated_at
 */
class Theme extends Model
{
    protected $table = 'themes';

    protected $fillable = [
        'code',
        'label',
        'order',
        'icon_url'
    ];
}
