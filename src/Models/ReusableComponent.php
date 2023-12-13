<?php

namespace Webid\Druid\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReusableComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
