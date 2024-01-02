<?php

namespace Webid\Druid\Http\Middleware;

use Webid\Druid\Repositories\PageRepository;

class RedirectionParentChild
{
    public function __construct(
        private readonly PageRepository $pageRepository,
    ) {
    }

    public function handle($request, $next)
    {
        $page = $this->pageRepository->findOrFailBySlug(last($request->segments()));

        $path = $request->path();
        $fullPath = $page->getFullPathUrl();

        if ($path !== $fullPath) {
            return redirect($fullPath, 301);
        }

        return $next($request);
    }
}
