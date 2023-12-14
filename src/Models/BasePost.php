<?php

namespace Webid\Druid\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webid\Druid\Enums\PostStatus;

/**
 * @property string $title
 * @property string $slug
 * @property string|null $post_image
 * @property string|null $post_image_alt
 * @property PostStatus $status
 * @property string $lang
 * @property string|null $extrait
 * @property array $content
 * @property bool $is_top_article
 *
 * @property bool $indexation
 * @property bool $follow
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $opengraph_title
 * @property string|null $opengraph_description
 * @property string|null $opengraph_picture
 * @property string|null $opengraph_picture_alt
 *
 * @property \Illuminate\Support\Carbon|null $published_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webid\Druid\Models\Category[] $categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 */
class BasePost extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'post_image',
        'post_image_alt',
        'status',
        'lang',
        'extrait',
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
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_post', 'post_id', 'category_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
