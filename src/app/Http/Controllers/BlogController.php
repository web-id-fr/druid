<?php

namespace Webid\Druid\App\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\View\View;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Http\Resources\PostResource;
use Webid\Druid\App\Models\Category;
use Webid\Druid\App\Models\Post;
use Webid\Druid\App\Repositories\CategoryRepository;
use Webid\Druid\App\Repositories\PostRepository;

class BlogController
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly CategoryRepository $categoryRepository,
    ) {
    }

    public function indexMultilingual(Langs $lang): View|AnonymousResourceCollection
    {
        $posts = $this->postRepository->allPaginatedByLang(getPostsPerPage(), $lang, ['categories']);

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

        $posts = $this->postRepository->allByCategoryAndLangPaginated($category, getPostsPerPage(), $lang, ['categories']);

        if (config('cms.views.type') === 'api') {
            return PostResource::collection($posts);
        }

        return view('druid::blog.index', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }

    public function showMultilingual(Langs $lang, Category $category, Post $post): View|PostResource
    {
        $type = config('cms.views.type');

        if ($type === 'api') {
            return $this->showApi($post);
        }

        return $this->showBlade($post);
    }

    public function index(): View|AnonymousResourceCollection {
        $posts = $this->postRepository->allPaginated(getPostsPerPage(), ['categories']);

        if (config('cms.views.type') === 'api') {
            return PostResource::collection($posts);
        }

        return view('druid::blog.index', [
            'posts' => $posts,
        ]);
    }

    public function indexByCategory(Category $category): View|AnonymousResourceCollection
    {
        $posts = $this->postRepository->allByCategoryPaginated($category, getPostsPerPage(), ['categories']);

        if (config('cms.views.type') === 'api') {
            return PostResource::collection($posts);
        }

        return view('druid::blog.index', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }

    public function show(Category $category, Post $post): View|PostResource
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
