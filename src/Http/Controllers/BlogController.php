<?php

namespace Webid\Druid\Http\Controllers;

use Webid\Druid\Enums\Langs;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Category;
use Webid\Druid\Models\Post;
use Webid\Druid\Repositories\CategoryRepository;
use Webid\Druid\Repositories\PostRepository;
use Webid\Druid\Services\ContentRenderer\ContentRenderer;

class BlogController
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly ContentRenderer $contentRenderer,
    ) {}

    public function indexMultilingual(Langs $lang): mixed
    {
        $posts = $this->postRepository->allPaginatedByLang(Druid::getPostsPerPage(), $lang, ['categories']);
        $categories = $this->categoryRepository->allByLang($lang);

        return $this->contentRenderer->render('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    public function indexByCategoryMultilingual(Langs $lang, Category $category): mixed
    {
        /** @var Category $category */
        $category = $this->categoryRepository->categoryByLang($category, $lang);

        $posts = $this->postRepository->allByCategoryAndLangPaginated($category, Druid::getPostsPerPage(), $lang, ['categories']);
        $categories = $this->categoryRepository->allByLang($lang);

        return $this->contentRenderer->render('blog.index', [
            'category' => $category,
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    public function showMultilingual(Langs $lang, Category $category, Post $post): mixed
    {
        $post->load(['thumbnail', 'openGraphPicture']);

        return $this->contentRenderer->render('blog.show', [
            'post' => $post,
        ]);
    }

    public function index(): mixed
    {
        $posts = $this->postRepository->allPaginated(Druid::getPostsPerPage(), ['categories']);
        $categories = $this->categoryRepository->all();

        return $this->contentRenderer->render('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    public function indexByCategory(Category $category): mixed
    {
        $posts = $this->postRepository->allByCategoryPaginated($category, Druid::getPostsPerPage(), ['categories']);
        $categories = $this->categoryRepository->all();

        return $this->contentRenderer->render('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    public function show(Category $category, Post $post): mixed
    {
        $post->load(['thumbnail', 'openGraphPicture']);

        return $this->contentRenderer->render('blog.show', [
            'post' => $post,
        ]);
    }
}
