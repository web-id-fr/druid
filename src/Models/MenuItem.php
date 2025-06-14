<?php

namespace Webid\Druid\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Webid\Druid\Enums\MenuItemTarget;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Contracts\IsMenuable;

/**
 * @property int $id
 * @property string|null $label
 * @property string|null $custom_url
 * @property MenuItemTarget $target
 * @property string $type
 * @property-read Menu $menu
 * @property-read IsMenuable|null $model
 * @property-read Collection<int, MenuItem> $children
 */
class MenuItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'target' => MenuItemTarget::class,
    ];

    protected $guarded = [
        'id',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function menu(): BelongsTo
    {
        /** @var class-string<Model> $model */
        $model = Druid::getModel('menu');

        return $this->belongsTo($model);
    }

    public function children(): HasMany
    {
        /** @var class-string<Model> $model */
        $model = Druid::getModel('menu_item');

        return $this->hasMany($model, 'parent_item_id');
    }
}
