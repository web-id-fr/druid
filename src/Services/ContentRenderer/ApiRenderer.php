<?php

namespace Webid\Druid\Services\ContentRenderer;

use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Webid\Druid\Http\Resources\PageResource;
use Webid\Druid\Http\Resources\PostResource;

class ApiRenderer implements ContentRenderer
{
    /**
     * @param  array<string, mixed>  $context
     */
    public function render(string $view, array $context): AnonymousResourceCollection|PostResource|PageResource
    {
        return match ($view) {
            'blog.index' => PostResource::collection($context['posts']),
            /** @phpstan-ignore-next-line */
            'blog.show' => PostResource::make($context['post']->load('categories')),
            'page.show' => PageResource::make($context['page']),
            default => throw new Exception('Unhandled view')
        };
    }
}
