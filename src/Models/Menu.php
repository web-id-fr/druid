<?php

namespace Webid\Druid\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $slug
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webid\Druid\Models\MenuItem[] $items
 */
class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'title',
        'slug',
    ];
}
