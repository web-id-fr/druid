<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webid\Druid\Models\BasePost;

/**
 * @property string $name
 * @property string $slug
 * @property string $lang
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webid\Druid\Models\BasePost[] $posts
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'lang',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BasePost::class, 'category_post', 'category_id', 'post_id');
    }
}
