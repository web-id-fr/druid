<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\Database\Factories\CategoryFactory;
use Webid\Druid\Database\Factories\PostFactory;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Models\Category;
use Webid\Druid\Models\Post;

trait PostCreator
{
    protected function createPost(array $params = []): Post
    {
        /** @var Post $post */
        $post = PostFactory::new()->create($params);

        return $post;
    }

    protected function createPostWithCategory(array $postParams = [], array $categoryParams = []): Post
    {
        /** @var Category $category */
        $category = CategoryFactory::new()->create($categoryParams);

        /** @var Post $post */
        $post = PostFactory::new()->forCategory($category)->create($postParams);

        return $post;
    }

    protected function createPostInEnglish(array $params = []): Post
    {
        /** @var Post $post */
        $post = PostFactory::new()->create([...$params, 'lang' => Langs::EN->value]);

        return $post;
    }

    protected function createFrenchTranslationPost(array $params = [], ?Post $fromPost = null): Post
    {
        if ($fromPost) {
            $params['translation_origin_model_id'] = $fromPost->getKey();
        }

        /** @var Post $post */
        $post = PostFactory::new()->create([...$params, 'lang' => Langs::FR->value]);

        return $post;
    }

    protected function createGermanTranslationPost(array $params = [], ?Post $fromPost = null): Post
    {
        if ($fromPost) {
            $params['translation_origin_model_id'] = $fromPost->getKey();
        }

        /** @var Post $post */
        $post = PostFactory::new()->create([...$params, 'lang' => Langs::DE->value]);

        return $post;
    }
}
