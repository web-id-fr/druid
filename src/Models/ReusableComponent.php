<?php

namespace Webid\Druid\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property array<int, array<int, mixed>> $content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class ReusableComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'searchable_content',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
