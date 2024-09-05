<?php

namespace Webid\Druid\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Traits\IsTranslatable;

/**
 * @property string $name
 * @property string $slug
 * @property ?Langs $lang
 * @property int|null $translation_origin_model_id
 * @property-read Category $translationOriginModel
 * @property-read Collection|Post[] $posts
 */
class Category extends Model
{
    use HasFactory;
    use IsTranslatable;

    protected $table = 'categories';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'lang',
        'translation_origin_model_id',
    ];

    protected $casts = [
        'lang' => Langs::class,
    ];

    public function posts(): BelongsToMany
    {
        /** @var class-string<Model> $model */
        $model = Druid::getModel('post');

        return $this->belongsToMany($model, 'category_post', 'category_id', 'post_id');
    }

    public function resolveRouteBinding($value, $field = null): Category
    {
        return Druid::isMultilingualEnabled() ? $this->where('slug', $value)->where('lang', Druid::getCurrentLocale())->firstOrFail() :
            $this->where('slug', $value)->firstOrFail();
    }
}
