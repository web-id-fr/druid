<?php

namespace Webid\Druid\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Webid\Druid\Enums\PageStatus;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Contracts\IsMenuable;
use Webid\Druid\Models\Traits\CanRenderContent;
use Webid\Druid\Models\Traits\IsTranslatable;
use Webid\Druid\Services\ComponentSearchContentExtractor;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property array<int, array<mixed>> $content
 * @property string|null $searchable_content
 * @property PageStatus $status
 * @property string|null $lang
 * @property int|null $parent_page_id
 * @property int|null $translation_origin_model_id
 * @property bool $disable_indexation
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $opengraph_title
 * @property string|null $opengraph_description
 * @property int|null $opengraph_picture
 * @property string|null $opengraph_picture_alt
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Page|null $parent
 * @property-read Page $translationOriginModel
 * @property-read Page $translationOrigin
 * @property-read Collection<int, Page> $translations
 */
class Page extends Model implements IsMenuable
{
    use CanRenderContent;
    use HasFactory;
    use IsTranslatable;
    use SoftDeletes;

    protected $table = 'pages';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'content' => 'array',
        'status' => PageStatus::class,
        'disable_indexation' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        /** @var class-string<Model> $model */
        $model = Druid::getModel('page');

        return $this->belongsTo($model, 'parent_page_id');
    }

    public function translationOrigin(): BelongsTo
    {
        /** @var class-string<Model> $model */
        $model = Druid::getModel('page');

        return $this->belongsTo($model, 'translation_origin_model_id');
    }

    public function translations(): HasMany
    {
        /** @var class-string<Model> $model */
        $model = Druid::getModel('page');

        return $this->hasMany($model, 'translation_origin_model_id');
    }

    public function translationForLang(string $locale): Page
    {
        return $this->translations->where('lang', $locale)->firstOrFail();
    }

    public function openGraphPicture(): BelongsTo
    {
        /** @var class-string<Model> $model
         */
        $model = Druid::getModel('media');

        return $this->belongsTo($model, 'opengraph_picture', 'id');
    }

    public function fullUrlPath(): string
    {
        $path = '';

        $parent = $this->parent;
        $parentsPath = '';
        while ($parent) {
            if ($parent->slug != 'index') {
                $parentsPath = $parent->slug.'/'.$parentsPath;
            }

            $parent = $parent->parent;
        }

        if (Druid::isMultilingualEnabled() && $this->slug !== 'index') {
            $path .= $this->lang ? $this->lang.'/' : '';
        }

        $path .= $parentsPath;
        if ($this->slug !== 'index') {
            $path .= $this->slug;
        }

        return $path;
    }

    public function url(): string
    {
        return url($this->fullUrlPath());
    }

    public function getMenuLabel(): string
    {
        return $this->title;
    }

    public function resolveRouteBinding($value, $field = null): Page
    {
        return Druid::isMultilingualEnabled() ? $this->where('slug', $value)->where('lang', Druid::getCurrentLocaleKey())->firstOrFail() :
            $this->where('slug', $value)->firstOrFail();
    }

    protected static function boot(): void
    {
        parent::boot();
        static::saving(function (Page $model) {
            /** @var ComponentSearchContentExtractor $searchableContentExtractor */
            $searchableContentExtractor = app(ComponentSearchContentExtractor::class);

            $model->searchable_content = $searchableContentExtractor->extractSearchableContentFromBlocks($model->content);
        });
    }

    public function incrementSlug(string $slug, ?string $lang = null): string
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
        return $this->status === PageStatus::PUBLISHED;
    }
}
