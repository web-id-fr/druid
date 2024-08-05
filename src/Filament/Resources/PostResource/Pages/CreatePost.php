<?php

namespace Webid\Druid\Filament\Resources\PostResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Filament\Resources\PostResource;
use Webid\Druid\Models\Post;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function afterCreate(): void
    {
        /** @var Post $post */
        $post = $this->record;

        if (Druid::isMultilingualEnabled()) {
            if ($post->lang === Druid::getDefaultLocale()) {
                $post->update(['translation_origin_model_id' => $post->getKey()]);
            }
        }

        $post->save();
    }
}
