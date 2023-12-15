<?php

namespace Webid\Druid\Models;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webid\Druid\Enums\PageStatus;
use Webid\Druid\Services\ComponentContentHtmlFormatter;

/**
 * @property string         $title
 * @property string         $slug
 * @property array          $content
 * @property string|null    $html_content
 * @property PageStatus     $status
 * @property string|null    $lang
 * @property int|null       $parent_page_id
 * @property bool           $indexation
 * @property string|null    $meta_title
 * @property string|null    $meta_description
 * @property string|null    $meta_keywords
 * @property string|null    $opengraph_title
 * @property string|null    $opengraph_description
 * @property string|null    $opengraph_picture
 * @property string|null    $opengraph_picture_alt
 * @property Carbon|null    $published_at
 * @property Carbon|null    $created_at
 * @property Carbon|null    $updated_at
 * @property Carbon|null    $deleted_at
 * @property-read Page|null $parent
 */
abstract class BasePage extends Model
{
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
        'parent_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'content' => 'array',
        'status' => PageStatus::class,
    ];

    public function getParentKeyName(): string
    {
        return 'parent_page_id';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, $this->getParentKeyName());
    }

    protected static function boot(): void
    {
        parent::boot();
        static::saving(function (Page $model) {
            /** @var ComponentContentHtmlFormatter $htmlFormatter */
            $htmlFormatter = app(ComponentContentHtmlFormatter::class);

            $model->html_content = $htmlFormatter->convertToHtml($model->content);
        });
    }
}
