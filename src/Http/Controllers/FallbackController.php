<?php

namespace Webid\Druid\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webid\Druid\Enums\RenderType;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Http\Resources\PageResource;
use Webid\Druid\Repositories\PageRepository;
use Webmozart\Assert\Assert;

class FallbackController extends Controller
{
    public function __construct(
        private readonly PageRepository $pageRepository,
        private readonly PageController $pageController,
    ) {
    }

    public function show(Request $request): PageResource|View
    {
        $type = config('cms.views.type');

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

        if ($type === RenderType::API->value) {
            return $this->pageController->showApi($page);
        }

        return $this->pageController->show($page);
    }
}
