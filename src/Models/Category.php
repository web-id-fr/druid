<?php

namespace Webid\Druid\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Traits\IsTranslatable;

/**
 * @property string $name
 * @property string $slug
 * @property string $lang
 * @property int|null $translation_origin_model_id
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
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Druid::getModel('post'), 'category_post', 'category_id', 'post_id');
    }
}
