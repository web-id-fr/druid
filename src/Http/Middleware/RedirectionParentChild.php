<?php

namespace Webid\Druid\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\Response;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Repositories\PageRepository;
use Webmozart\Assert\Assert;

class RedirectionParentChild
{
    public function __construct(
        private readonly PageRepository $pageRepository,
    ) {}

    public function handle(Request $request, \Closure $next): Response|Redirector
    {
        $path = $request->path();
        $slugs = explode('/', $path);
        $lastSegment = end($slugs);
        $lang = reset($slugs);

        $page = $this->pageRepository->findOrFailBySlug($lastSegment);

        if (Druid::isMultilingualEnabled()) {
            if (! array_key_exists($lang, Druid::getLocales())) {
                abort(404);
            }

            $page = $this->pageRepository->findOrFailBySlugAndLang($lastSegment, $lang);
        }

        $path = $request->path();
        $fullPath = $page->fullUrlPath();

        if ($path !== $fullPath) {
            return redirect($fullPath, 301);
        }

        return $next($request);
    }
}
