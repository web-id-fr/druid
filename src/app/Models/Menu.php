<?php

namespace Webid\Druid\App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Models\Traits\IsTranslatable;
use Webid\Druid\Database\Factories\MenuFactory;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $translation_origin_model_id
 * @property-read Collection $items
 * @property-read Collection<string, MenuItem> $level0Items
 * @property-read Menu|null $translationOriginModel
 * @property-read Collection<int, Menu> $translations
 */
class Menu extends Model
{
    use HasFactory;
    use IsTranslatable;

    protected $table = 'menus';

    protected $fillable = [
        'title',
        'slug',
    ];

    protected $casts = [
        'lang' => Langs::class,
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public function level0Items(): HasMany
    {
        return $this->items()->whereNull('parent_item_id');
    }

    protected static function newFactory(): MenuFactory
    {
        return new MenuFactory();
    }
}
