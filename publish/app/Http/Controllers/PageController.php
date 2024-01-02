<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;
use Webid\Druid\Http\Resources\PageResource;

class PageController extends Controller
{
    public function show(Page $page): View
    {
        return view('druid::page.show', [
            'page' => $page,
        ]);
    }

    public function showApi(Page $page): PageResource
    {
        return PageResource::make($page);
    }
}
