<?php

namespace Webid\Druid\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $key
 * @property string $value
 **/
class Settings extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public static function get(string $key = '*', mixed $default = null): mixed
    {
        $settings = [];

        Settings::all()->each(function ($setting) use (&$settings) {
            data_set($settings, $setting->key, $setting->value);
        });

        if ($key === '*') {
            return $settings;
        }

        return data_get($settings, $key, $default);
    }

    public static function set(string $key, mixed $value): mixed
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        return $setting->value;
    }
}
