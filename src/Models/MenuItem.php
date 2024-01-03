<?php

namespace Webid\Druid\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'order',
        'parent_item_id',
        'label',
        'custom_url',
        'model_type',
        'model_id',
        'target',
    ];


    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
