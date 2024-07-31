<?php

namespace Webid\Druid\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Webid\Druid\Enums\Langs;
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
 * @property array $content
 * @property string|null $searchable_content
 * @property PageStatus $status
 * @property Langs|null $lang
 * @property int|null $parent_page_id
 * @property int|null $translation_origin_model_id
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
 * @property-read Page $translationOriginModel
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
        'lang' => Langs::class,
    ];

    public function parent(): BelongsTo
    {
        /** @var class-string<Model> $model */
        $model = Druid::getModel('page');

        return $this->belongsTo($model, 'parent_page_id');
    }

    public function fullUrlPath(): string
    {
        $path = '';

        $parent = $this->parent;
        $parentsPath = '';
        while ($parent) {
            $parentsPath = $parent->slug.'/'.$parentsPath;
            $parent = $parent->parent;
        }

        if (Druid::isMultilingualEnabled()) {
            $path .= $this->lang ? $this->lang->value.'/' : '';
        }

        $path .= $parentsPath.$this->slug;

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
        return Druid::isMultilingualEnabled() ? $this->where('slug', $value)->where('lang', Druid::getCurrentLocale())->firstOrFail() :
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
}
