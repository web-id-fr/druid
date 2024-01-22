<?php

namespace Webid\Druid\App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webid\Druid\App\Enums\Langs;

/**
 * @property Langs $lang
 */
trait IsTranslatable
{
    public function translationOriginModel(): BelongsTo
    {
        return $this->belongsTo($this, 'translation_origin_model_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany($this, 'translation_origin_model_id')
            ->whereNot('translation_origin_model_id', $this->getKey());
    }
}
