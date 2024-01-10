<?php

namespace Webid\Druid\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\Response;
use Webid\Druid\Repositories\PageRepository;
use Webmozart\Assert\Assert;

class RedirectionParentChild
{
    public function __construct(
        private readonly PageRepository $pageRepository,
    ) {
    }

    public function handle(Request $request, \Closure $next): Response|Redirector
    {
        $lastSegment = last($request->segments());
        Assert::string($lastSegment);

        $page = $this->pageRepository->findOrFailBySlug($lastSegment);

        $path = $request->path();
        $fullPath = $page->getFullPathUrl();

        if ($path !== $fullPath) {
            return redirect($fullPath, 301);
        }

        return $next($request);
    }
}
