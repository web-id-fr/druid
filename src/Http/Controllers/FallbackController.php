<?php

namespace Webid\Druid\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Webid\Druid\Http\Resources\PageResource;
use Webid\Druid\Repositories\PageRepository;

class FallbackController extends Controller
{
    public function __construct(
        private readonly PageRepository $pageRepository,
        private readonly PageController $pageController,
    ) {

    }

    public function show(Request $request): PageResource
    {
        try {
            $page = $this->pageRepository->findOrFailBySlug(last($request->segments()));
        } catch (ModelNotFoundException $exception) {
            abort(404);
        }

        return $this->pageController->show($page);
    }
}
