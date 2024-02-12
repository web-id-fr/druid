<?php

namespace Webid\Druid\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Webid\Druid\App\Enums\MenuItemTarget;
use Webid\Druid\App\Models\Contracts\IsMenuable;

/**
 * @property int $id
 * @property string|null $label
 * @property string|null $custom_url
 * @property MenuItemTarget $target
 * @property-read Menu $menu
 * @property-read IsMenuable|null $model
 * @property-read Collection<int, MenuItem> $children
 */
class MenuItem extends Model
{
    use HasFactory;

    protected $casts = [
        'target' => MenuItemTarget::class,
    ];

    protected $fillable = [
        'menu_id',
        'order',
        'parent_item_id',
        'label',
        'custom_url',
        'model_type',
        'model_id',
        'target',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_item_id');
    }
}
