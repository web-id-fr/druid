<?php

namespace Webid\Druid\Services;

use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Webid\Druid\Repositories\PageRepository;
use Webid\Druid\Repositories\PostRepository;

class EnvironmentGuesserService
{
    public function __construct(
        private readonly Repository $config,
        private readonly PostRepository $postRepository,
        private readonly PageRepository $pageRepository
    ) {
    }

    public function getEnvironment(string $path, string $locale): ?string
    {
        /** @var string $blogPrefix */
        $blogPrefix = $this->config->get('cms.blog.prefix');
        $slug = basename($path);

        if (Str::contains($path, "/{$blogPrefix}/")) {
            return $this->getBlogPageForLang($slug, $locale);
        }

        return $this->getPageForLang($slug, $locale);
    }

    private function getBlogPageForLang(string $slug, string $locale): ?string
    {
        try {
            $blog = $this->postRepository->findOrFailBySlugAndLang($slug, $locale);

            return $blog->url();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    private function getPageForLang(string $slug, string $locale): ?string
    {
        try {
            $page = $this->pageRepository->findOrFailBySlugAndLang($slug, $locale);

            return $page->url();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }
}
