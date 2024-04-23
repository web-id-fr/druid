<?php

namespace Webid\Druid\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\View\View;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Http\Resources\PostResource;
use Webid\Druid\Models\Category;
use Webid\Druid\Models\Post;
use Webid\Druid\Repositories\CategoryRepository;
use Webid\Druid\Repositories\PostRepository;

class BlogController
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly CategoryRepository $categoryRepository,
    ) {
    }

    public function indexMultilingual(Langs $lang): View|AnonymousResourceCollection
    {
        $posts = $this->postRepository->allPaginatedByLang(Druid::getPostsPerPage(), $lang, ['categories']);

        if (config('cms.views.type') === 'api') {
            return PostResource::collection($posts);
        }

        return view('druid::blog.index', [
            'posts' => $posts,
        ]);
    }

    public function indexByCategoryMultilingual(Langs $lang, Category $category): View|AnonymousResourceCollection
    {
        /** @var Category $category */
        $category = $this->categoryRepository->categoryByLang($category, $lang);

        $posts = $this->postRepository->allByCategoryAndLangPaginated($category, Druid::getPostsPerPage(), $lang, ['categories']);

        if (config('cms.views.type') === 'api') {
            return PostResource::collection($posts);
        }

        return view('druid::blog.index', [
            'posts' => $posts,
        ]);
    }

    public function showMultilingual(Langs $lang, Post $post): View|PostResource
    {
        $type = config('cms.views.type');

        if ($type === 'api') {
            return $this->showApi($post);
        }

        return $this->showBlade($post);
    }

    public function index(): View|AnonymousResourceCollection
    {
        $posts = $this->postRepository->allPaginated(Druid::getPostsPerPage(), ['categories']);

        if (config('cms.views.type') === 'api') {
            return PostResource::collection($posts);
        }

        return view('druid::blog.index', [
            'posts' => $posts,
        ]);
    }

    public function indexByCategory(Category $category): View|AnonymousResourceCollection
    {
        $posts = $this->postRepository->allByCategoryPaginated($category, Druid::getPostsPerPage(), ['categories']);

        if (config('cms.views.type') === 'api') {
            return PostResource::collection($posts);
        }

        return view('druid::blog.index', [
            'posts' => $posts,
        ]);
    }

    public function show(Post $post): View|PostResource
    {
        $post->loadMissing('thumbnail');
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
