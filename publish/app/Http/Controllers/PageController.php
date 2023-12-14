<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Webid\Druid\Http\Resources\PageResource;

class PageController extends Controller
{
    public function show(Page $page): PageResource
    {
        return PageResource::make($page);
    }
}
