<?php

declare(strict_types=1);

namespace Webid\Druid\Http\Controllers;

use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Page;
use Webid\Druid\Services\ContentRenderer\ContentRenderer;

class PageController extends Controller
{
    public function __construct(private readonly ContentRenderer $contentRenderer)
    {

    }

    public function show(Page $page): mixed
    {
        if (Druid::isMultilingualEnabled()) {
            $page->loadMissing(['translations', 'openGraphPicture']);
        }

        return $this->contentRenderer->render('page.show', ['page' => $page]);
    }
}
