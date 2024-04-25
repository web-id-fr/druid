<?php

namespace Webid\Druid\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\Models\Settings;

class SettingsRepository
{
    private Settings $model;

    public function __construct()
    {
        $this->model = new Settings();
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function findSettingByKeyName(string $keyName): ?Model
    {
        return $this->model->newQuery()
            ->where('key', $keyName)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $values
     */
    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->newQuery()->updateOrCreate($attributes, $values);
    }
}
