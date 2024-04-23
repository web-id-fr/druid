<?php

namespace Webid\Druid\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\Repositories\SettingsRepository;

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
        $settingRepository = app(SettingsRepository::class);

        /** @var Collection<Settings> $settingsCollection */
        $settingsCollection = $settingRepository->all()->pluck('value', 'key');

        if ($key === '*') {
            return $settingsCollection->toArray();
        }

        return $settingsCollection->get($key, $default);
    }

    public static function set(string $key, mixed $value): mixed
    {
        $settingRepository = app(SettingsRepository::class);

        /** @var Settings $setting */
        $setting = $settingRepository->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        return $setting->value;
    }
}
