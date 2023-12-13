<?php

namespace Webid\Druid\Models;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class BasePage extends Model
{
    use HasFactory;

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
        'publish_at' => 'datetime',
    ];

    public function getParentKeyName(): string
    {
        return 'parent_page_id';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, $this->getParentKeyName());
    }
}
