<?php

namespace Webid\Druid\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\View\View;
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

        $lastSegment = last($request->segments());
        Assert::string($lastSegment);

        try {
            $page = $this->pageRepository->findOrFailBySlug($lastSegment);
        } catch (ModelNotFoundException $exception) {
            abort(404);
        }

        if ($type === 'api') {
            return $this->pageController->showApi($page);
        }

        return $this->pageController->show($page);
    }
}
