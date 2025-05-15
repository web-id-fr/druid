<?php

namespace Webid\Druid\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $lang
 */
trait IsTranslatable
{
    public function translationOriginModel(): BelongsTo
    {
        return $this->belongsTo(self::class, 'translation_origin_model_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(self::class, 'translation_origin_model_id')
            ->where('id', '!=', $this->getKey());
    }
}
