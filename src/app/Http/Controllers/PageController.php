<?php

declare(strict_types=1);

namespace Webid\Druid\App\Http\Controllers;

use Illuminate\View\View;
use Webid\Druid\App\Http\Resources\PageResource;
use Webid\Druid\App\Models\Page;

class PageController extends Controller
{
    public function show(Page $page): View
    {
        if (isMultilingualEnabled()) {
            $page->loadMissing('translations');
        }

        return view('druid::page.page', [
            'page' => PageResource::make($page)->toObject(),
        ]);
    }

    public function showApi(Page $page): PageResource
    {
        return PageResource::make($page);
    }
}
