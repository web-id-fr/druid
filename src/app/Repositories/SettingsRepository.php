<?php

namespace Webid\Druid\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\App\Models\Settings;

class SettingsRepository
{
    public function __construct(private readonly Settings $model)
    {
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
