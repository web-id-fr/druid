<?php

namespace Webid\Druid\Models;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Enums\PageStatus;
use Webid\Druid\Models\Contracts\IsMenuable;
use Webid\Druid\Models\Traits\CanRenderContent;
use Webid\Druid\Services\ComponentSearchContentExtractor;

/**
 * @property string $title
 * @property string $slug
 * @property array $content
 * @property string|null $searchable_content
 * @property PageStatus $status
 * @property Langs|null $lang
 * @property int|null $parent_page_id
 * @property int|null $translation_origin_page_id
 * @property bool $indexation
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $opengraph_title
 * @property string|null $opengraph_description
 * @property string|null $opengraph_picture
 * @property string|null $opengraph_picture_alt
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Page|null $parent
 * @property-read Page $translationOriginPage
 * @property-read Collection<int, Page> $translations
 */
abstract class BasePage extends Model implements IsMenuable
{
    use CanRenderContent;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pages';

    protected $fillable = [
        'title',
        'slug',
        'lang',
        'status',
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
        'parent_page_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'content' => 'array',
        'status' => PageStatus::class,
        'lang' => Langs::class,
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_page_id');
    }

    public function translationOriginPage(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'translation_origin_page_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Page::class, 'translation_origin_page_id')
            ->whereNot('translation_origin_page_id', $this->getKey());
    }

    public function fullUrlPath(): string
    {
        $path = '';

        if (isMultilingualEnabled()) {
            $path .= $this->lang ? $this->lang->value : config('cms.default_locale');
            $path .= '/';
        }

        $path .= $this->slug;

        $parent = $this->parent;
        while ($parent) {
            $path = $parent->slug.'/'.$path;
            $parent = $parent->parent;
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

    protected static function boot(): void
    {
        parent::boot();
        static::saving(function (BasePage $model) {
            /** @var ComponentSearchContentExtractor $searchableContentExtractor */
            $searchableContentExtractor = app(ComponentSearchContentExtractor::class);

            $model->searchable_content = $searchableContentExtractor->extractSearchableContentFromBlocks($model->content);
        });
    }
}
