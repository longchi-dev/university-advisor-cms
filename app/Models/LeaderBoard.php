<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $rank
 * @property string $name
 * @property string $phone
 * @property Carbon $week_start_date
 * @property int $week_number
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class LeaderBoard extends Model
{
    use HasFactory;

    protected $table = 'leaderboards';
    protected $hidden = ['phone'];

    protected $fillable = [
        'rank',
        'name',
        'phone',
        'week_start_date',
        'week_number',
    ];

    protected $casts = [
        'week_start_date' => 'date',
        'week_number' => 'integer',
        'rank' => 'integer',
    ];

    public function getMaskPhone(): string
    {
        if (!$this->phone) {
            return '';
        }
        return sprintf(
            '%sxxxx%s',
            substr($this->phone, 0, 4),
            substr($this->phone, -3),
        );
    }
}
