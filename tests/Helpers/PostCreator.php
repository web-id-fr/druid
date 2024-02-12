<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Models\Post;

trait PostCreator
{
    protected function createPost(array $params = []): Post
    {
        return Post::factory($params)->create();
    }

    protected function createPostInEnglish(array $params = []): Post
    {
        return Post::factory([...$params, 'lang' => Langs::EN->value])->create();
    }

    protected function createFrenchTranslationPost(array $params = [], ?Post $fromPost = null): Post
    {
        if ($fromPost) {
            $params['translation_origin_model_id'] = $fromPost->getKey();
        }

        return Post::factory([...$params, 'lang' => Langs::FR->value])->create();
    }

    protected function createGermanTranslationPost(array $params = [], ?Post $fromPost = null): Post
    {
        if ($fromPost) {
            $params['translation_origin_model_id'] = $fromPost->getKey();
        }

        return Post::factory([...$params, 'lang' => Langs::DE->value])->create();
    }
}
