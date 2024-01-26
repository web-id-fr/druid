<?php

namespace Webid\Druid\App\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\View\View;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Http\Resources\PostResource;
use App\Model\Post;
use Webid\Druid\App\Repositories\PostRepository;

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

    public function show(Post $post): View|PostResource
    {
        $type = config('cms.views.type');

        if ($type === 'api') {
            return $this->showApi($post);
        }

        return $this->showBlade($post);
    }

    public function showMultilingual(Langs $lang, Post $post): View|PostResource
    {
        $type = config('cms.views.type');

        if ($type === 'api') {
            return $this->showApi($post);
        }

        return $this->showBlade($post);
    }

    public function showApi(Post $post): PostResource
    {
        return PostResource::make($post->load('categories'));
    }

    public function showBlade(Post $post): View
    {
        return view('druid::blog.show', [
            'post' => PostResource::make($post->load('categories'))->toObject(),
        ]);
    }
}
