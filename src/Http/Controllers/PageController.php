<?php

declare(strict_types=1);

namespace Webid\Druid\Http\Controllers;

use Illuminate\View\View;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Http\Resources\PageResource;
use Webid\Druid\Models\Page;

class PageController extends Controller
{
    public function show(Page $page): View
    {
        if (Druid::isMultilingualEnabled()) {
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
