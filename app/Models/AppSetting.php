<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * @property string $setting_group
 * @property string $setting_key
 * @property string $setting_value
 * @property string $value_type
 * @property boolean $autoload
 * @property Collection $created_at
 * @property Collection $updated_at
 */
class AppSetting extends Model
{
    const SETTING_KEY = 'app_settings_autoload';
    protected $fillable = [
        'setting_group',
        'setting_key',
        'setting_value',
        'value_type',
        'autoload',
    ];

    protected $casts = [
        'autoload' => 'boolean',
    ];

    /**
     * Get value by group & key
     */
    public static function getValue(string $group, string $key, bool $useAutoloadCache = true)
    {
//        if ($useAutoloadCache) {
//            $settings = Cache::driver('redis')->rememberForever(static::SETTING_KEY, function () {
//                return static::query()
//                    ->where('autoload', true)
//                    ->get()
//                    ->groupBy('setting_group')
//                    ->map(fn($items) => $items->keyBy('setting_key'));
//            });
//
//            return $settings[$group][$key]->castValue() ?? null;
//        }

        return optional(
            static::query()
                ->where('setting_group', $group)
                ->where('setting_key', $key)
                ->first()
        )->castValue();
    }

    /**
     * Save or update value
     */
    public static function setValue(string $group, string $key, $value, string $type = 'string', bool $autoload = true): static
    {
        $model = static::query()
            ->updateOrCreate(
            [
                'setting_group' => $group,
                'setting_key' => $key
            ],
            [
                'setting_value' => $type === 'json' ? json_encode($value) : (string)$value,
                'value_type' => $type,
                'autoload' => $autoload
            ]
        );

//        if ($autoload) {
//            Cache::driver('redis')->forget(static::SETTING_KEY);
//        }

        return $model;
    }

    /**
     * Cast value based on value_type
     */
    public function castValue()
    {
        return match ($this->value_type) {
            'integer' => (int) $this->setting_value,
            'boolean' => (bool) $this->setting_value,
            'json' => json_decode($this->setting_value, true),
            default => $this->setting_value,
        };
    }
}
