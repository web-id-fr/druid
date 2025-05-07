<?php

namespace Webid\Druid\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Repositories\PageRepository;
use Webmozart\Assert\Assert;

class FallbackController extends Controller
{
    public function __construct(
        private readonly PageRepository $pageRepository,
        private readonly PageController $pageController,
    ) {
    }

    public function show(Request $request): mixed
    {
        $requestSegments = $request->segments();
        $firstSegment = head($requestSegments);
        $lastSegment = last($requestSegments);
        Assert::string($firstSegment);
        Assert::string($lastSegment);

        try {
            if (Druid::isMultilingualEnabled()) {
                $page = $this->pageRepository->findOrFailBySlugAndLang($lastSegment, $firstSegment);
            } else {
                $page = $this->pageRepository->findOrFailBySlug($lastSegment);
            }
        } catch (ModelNotFoundException $e) {
            abort(404);
        }

        Gate::authorize('view', $page);

        return $this->pageController->show($page);
    }
}
