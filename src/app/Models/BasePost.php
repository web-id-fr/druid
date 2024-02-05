<?php

namespace Webid\Druid\App\Models;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Enums\PostStatus;
use Webid\Druid\App\Models\Contracts\IsMenuable;
use Webid\Druid\App\Models\Traits\CanRenderContent;
use Webid\Druid\App\Models\Traits\IsTranslatable;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $post_image
 * @property string|null $post_image_alt
 * @property PostStatus $status
 * @property ?Langs $lang
 * @property string|null $excerpt
 * @property array $content
 * @property string|null $searchable_content
 * @property bool $is_top_article
 * @property bool $indexation
 * @property bool $follow
 * @property int $translation_origin_model_id
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $opengraph_title
 * @property string|null $opengraph_description
 * @property string|null $opengraph_picture
 * @property string|null $opengraph_picture_alt
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webid\Druid\Models\BaseCategory[] $categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read Post $translationOriginModel
 * @property-read Collection<int, Post> $translations
 */
abstract class BasePost extends Model implements IsMenuable
{
    use CanRenderContent;
    use HasFactory;
    use IsTranslatable;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'post_image',
        'post_image_alt',
        'status',
        'lang',
        'excerpt',
        'content',
        'indexation',
        'follow',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'opengraph_title',
        'opengraph_description',
        'opengraph_picture',
        'opengraph_picture_alt',
        'published_at',
        'is_top_article',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'content' => 'array',
        'status' => PostStatus::class,
        'lang' => Langs::class,
    ];

    public function fullUrlPath(): string
    {
        $path = '';

        if (isMultilingualEnabled()) {
            $path .= $this->lang ? $this->lang->value : config('cms.default_locale');
            $path .= '/';
        }

        $path .= config('cms.blog.prefix').'/';

        $path .= $this->slug;

        return $path;
    }

    public function url(): string
    {
        return url($this->fullUrlPath());
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BaseCategory::class, 'category_post', 'post_id', 'category_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getMenuLabel(): string
    {
        return $this->title;
    }
}
