<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Models\Post;

trait PostCreator
{
    protected function createPost(array $params = []): Post
    {
        /** @var Post $post */
        $post = Post::factory($params)->create();

        return $post;
    }

    protected function createPostInEnglish(array $params = []): Post
    {
        /** @var Post $post */
        $post = Post::factory([...$params, 'lang' => Langs::EN->value])->create();

        return $post;
    }

    protected function createFrenchTranslationPost(array $params = [], ?Post $fromPost = null): Post
    {
        if ($fromPost) {
            $params['translation_origin_model_id'] = $fromPost->getKey();
        }

        /** @var Post $post */
        $post = Post::factory([...$params, 'lang' => Langs::FR->value])->create();

        return $post;
    }

    protected function createGermanTranslationPost(array $params = [], ?Post $fromPost = null): Post
    {
        if ($fromPost) {
            $params['translation_origin_model_id'] = $fromPost->getKey();
        }

        /** @var Post $post */
        $post = Post::factory()->create([...$params, 'lang' => Langs::DE->value]);

        return $post;
    }
}
