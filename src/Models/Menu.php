<?php

namespace Webid\Druid\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $title
 * @property string $slug
 * @property-read Collection $items
 * @property-read Collection<string, MenuItem> $level0Items
 */
class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'title',
        'slug',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public function level0Items(): HasMany
    {
        return $this->items()->whereNull('parent_item_id');
    }
}
