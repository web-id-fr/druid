<?php

namespace Webid\Druid\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\View\View;
use Webid\Druid\Http\Resources\PostResource;
use Webid\Druid\Models\BasePost as Post;
use Webid\Druid\Models\Category;
use Webid\Druid\Repositories\PostRepository;

class BlogController
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    public function index(): View|AnonymousResourceCollection
    {
        $posts = $this->postRepository->all(['categories']);

        if (config('cms.views.type') === 'api') {
            return PostResource::collection($posts);
        }

        return view('druid::blog.index', [
            'posts' => $posts,
        ]);
    }

    public function show(Category $category, Post $post): View|PostResource
    {
        $type = config('cms.views.type');

        if ($type === 'api') {
            return $this->showApi($post);
        }

        return $this->showBlade($category, $post);
    }

    public function showApi(Post $post): PostResource
    {
        return PostResource::make($post->load('categories'));
    }

    public function showBlade(Category $category, Post $post): View
    {
        return view('druid::blog.show', [
            'post' => $post,
            'category' => $category,
        ]);
    }
}
