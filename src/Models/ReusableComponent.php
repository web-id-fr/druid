<?php

namespace Webid\Druid\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\Models\Traits\IsTranslatable;

/**
 * @property int $id
 * @property string $title
 * @property array<int, array<int, mixed>> $content
 * @property-read ReusableComponent|null $translationOriginModel
 * @property string $lang
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class ReusableComponent extends Model
{
    use HasFactory;
    use IsTranslatable;

    protected $fillable = [
        'title',
        'content',
        'searchable_content',
        'lang',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
