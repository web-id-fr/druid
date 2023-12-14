<?php

namespace Webid\Druid\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\Services\ComponentContentHtmlFormatter;

/**
 * @property string      $title
 * @property array       $content
 * @property string|null $html_content
 *
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 */
class ReusableComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'html_content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::saving(function (ReusableComponent $model) {
            /** @var ComponentContentHtmlFormatter $htmlFormatter */
            $htmlFormatter = app(ComponentContentHtmlFormatter::class);

            $model->html_content = $htmlFormatter->convertToHtml($model->content);
        });
    }
}
