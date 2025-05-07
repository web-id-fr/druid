<?php

namespace Webid\Druid\Services\ContentRenderer;

use Webid\Druid\Http\Resources\PageResource;
use Webid\Druid\Http\Resources\PostResource;

class BladeRenderer implements ContentRenderer
{
    /**
     * @param  array<string, mixed>  $context
     */
    public function render(string $view, array $context): mixed
    {
        $context = match ($view) {
            'blog.show' => [
                /** @phpstan-ignore-next-line */
                'post' => PostResource::make($context['post']->load('categories'))->toObject(),
            ],
            'page.show' => [
                'page' => PageResource::make($context['page'])->toObject(),
            ],
            default => $context
        };

        return view("druid::{$view}", $context);
    }
}
