<?php

namespace Webid\Druid\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webid\Druid\App\Models\Traits\IsTranslatable;
use Webid\Druid\Database\Factories\CategoryFactory;

/**
 * @property string $name
 * @property string $slug
 * @property string $lang
 * @property int|null $translation_origin_model_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webid\Druid\App\Models\Post[] $posts
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
        return $this->belongsToMany(Post::class, 'category_post', 'category_id', 'post_id');
    }

    protected static function newFactory(): CategoryFactory
    {
        return new CategoryFactory();
    }
}
