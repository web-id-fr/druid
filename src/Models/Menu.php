<?php

namespace Webid\Druid\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property array $content
 * @property-read \Illuminate\Database\Eloquent\Collection|\Webid\Druid\Models\MenuItem[] $items
 */
class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'title',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
