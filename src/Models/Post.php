<?php

namespace Webid\Druid\Models;

use Awcodes\Curator\Models\Media;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Enums\PostStatus;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Contracts\IsMenuable;
use Webid\Druid\Models\Traits\CanRenderContent;
use Webid\Druid\Models\Traits\IsTranslatable;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int|null $thumbnail_id
 * @property string|null $thumbnail_alt
 * @property PostStatus $status
 * @property ?Langs $lang
 * @property string|null $excerpt
 * @property array<int, array<mixed>> $content
 * @property string|null $searchable_content
 * @property bool $is_top_article
 * @property bool $disable_indexation
 * @property int $translation_origin_model_id
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $opengraph_title
 * @property string|null $opengraph_description
 * @property int|null $opengraph_picture
 * @property string|null $opengraph_picture_alt
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Category[] $categories
 * @property-read Collection|Authenticatable[] $users
 * @property-read Post $translationOriginModel
 * @property-read ?Media $thumbnail
 * @property-read Collection<int, Post> $translations
 * @property-read string $fullUrlPath
 * @property-read string $url
 */
class Post extends Model implements IsMenuable
{
    use CanRenderContent;
    use HasFactory;
    use IsTranslatable;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'thumbnail_id',
        'thumbnail_alt',
        'status',
        'lang',
        'excerpt',
        'content',
        'disable_indexation',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'opengraph_title',
        'opengraph_description',
        'opengraph_picture',
        'opengraph_picture_alt',
        'published_at',
        'is_top_article',
        'translation_origin_model_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'content' => 'array',
        'status' => PostStatus::class,
        'lang' => Langs::class,
        'disable_indexation' => 'boolean',
    ];

    public function fullUrlPath(): string
    {
        $path = '';

        if (Druid::isMultilingualEnabled()) {
            $path .= $this->lang ? $this->lang->value : config('cms.default_locale');
            $path .= '/';
        }

        $path .= config('cms.blog.prefix').'/';

        $path .= $this->categories->first()->slug.'/'.$this->slug;

        return $path;
    }

    public function url(): string
    {
        return url($this->fullUrlPath());
    }

    public function excerpt(): string
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }

        return Str::words(strip_tags($this->searchable_content), 100);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Druid::getModel('category'), 'category_post', 'post_id', 'category_id');
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(Druid::getModel('media'), 'thumbnail_id', 'id');
    }

    public function openGraphPicture(): BelongsTo
    {
        return $this->belongsTo(Druid::getModel('media'), 'opengraph_picture', 'id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Druid::getModel('user'), 'post_user', 'post_id', 'user_id');
    }

    public function getMenuLabel(): string
    {
        return $this->title;
    }

    public function resolveRouteBinding($value, $field = null): Post
    {
        return Druid::isMultilingualEnabled() ? $this->where('slug', $value)->where('lang', Druid::getCurrentLocale())->firstOrFail() :
            $this->where('slug', $value)->firstOrFail();
    }

    public function incrementSlug(string $slug, ?Langs $lang = null): string
    {
        $original = $slug;
        $count = 2;

        while (static::where('slug', $slug)->when(Druid::isMultilingualEnabled(), function ($query) use ($lang) {
            $query->where('lang', $lang);
        })->exists()) {
            $slug = "{$original}-".$count++;
        }

        return $slug;
    }

    public function isPublished(): bool
    {
        return $this->status === PostStatus::PUBLISHED;
    }
}
