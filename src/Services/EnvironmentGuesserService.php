<?php

namespace Webid\Druid\Services;

use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Support\Str;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Repositories\PageRepository;
use Webid\Druid\Repositories\PostRepository;
use Webmozart\Assert\Assert;

readonly class EnvironmentGuesserService
{
    public function __construct(
        private Repository $config,
        private PostRepository $postRepository,
        private PageRepository $pageRepository,
    ) {}

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

    public function getCurrentUrlForLang(string $destinationLocale): string
    {
        /** @phpstan-ignore-next-line  $requestSegments */
        $requestSegments = app()->request->segments();

        Assert::isArray($requestSegments);
        if (empty($requestSegments)) {
            return Druid::getFrontPage()?->translationOrigin->translationForLang($destinationLocale)->url();
        }

        $currentLocale = head($requestSegments);
        $currentSlug = last($requestSegments);
        Assert::string($currentLocale);
        Assert::string($currentSlug);

        $blogPrefix = Druid::getBlogPrefix();

        if (isset($requestSegments[1]) && $requestSegments[1] === $blogPrefix) {
            if (count($requestSegments) === 2) {
                return Druid::getBlogRootUrlForLang($destinationLocale);
            }

            try {
                $currentPost = $this->postRepository->findOrFailBySlugAndLang($currentSlug, $currentLocale);

                return $currentPost->url();
            } catch (ModelNotFoundException $e) {
                return Druid::getFrontPageUrl($destinationLocale);
            }
        }

        try {
            $page = $this->pageRepository->findOrFailBySlugAndLang($currentSlug, $currentLocale);

            return $page->translationOrigin->translationForLang($destinationLocale)->url();
        } catch (ModelNotFoundException|ItemNotFoundException) {
            return Druid::getFrontPageUrl($destinationLocale);
        }
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
