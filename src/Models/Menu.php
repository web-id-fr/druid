<?php

namespace Webid\Druid\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Traits\IsTranslatable;

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
    use SoftDeletes;

    protected $table = 'menus';

    protected $fillable = [
        'title',
        'slug',
        'lang',
    ];

    public function items(): HasMany
    {
        /** @var class-string<Model> $model */
        $model = Druid::getModel('menu_item');

        return $this->hasMany($model);
    }

    public function level0Items(): HasMany
    {
        return $this->items()->whereNull('parent_item_id');
    }
}
