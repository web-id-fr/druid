<?php

namespace Webid\Druid\Services\ContentRenderer;

use Exception;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Http\Resources\PageResource;
use Webid\Druid\Http\Resources\PostResource;
use Webmozart\Assert\Assert;

class BladeRenderer implements ContentRenderer
{
    /**
     * @param array<string, mixed> $context
     */
    public function render(string $view, array $context): mixed
    {
        match ($view) {
            /** @phpstan-ignore-next-line */
            'blog.show' => $context['post'] = PostResource::make($context['post']->load('categories'))->toObject(),
            'page.show' => $context['page'] = PageResource::make($context['page'])->toObject(),
            'blog.index' => $context['posts'] = PostResource::collection($context['posts']),
            default => throw new Exception('View does not exist.'),
        };

        if (Druid::isMenuModuleEnabled()) {
            $loadedMenus = config('cms.menu.loaded_menus');
            if (is_array($loadedMenus)) {
                $context['menus'] = [];
                foreach ($loadedMenus as $menuSlug) {
                    Assert::string($menuSlug);
                    try {
                        $context['menus'][$menuSlug] = Druid::isMultilingualEnabled() ?
                            Druid::getNavigationMenuBySlugAndLang($menuSlug, Druid::getCurrentLocaleKey()) :
                            Druid::getNavigationMenuBySlug($menuSlug);
                    } catch (\Exception) {
                    }
                }
            }
        }

        if (Druid::isMultilingualEnabled()) {
            $context['languageSwitcher'] = Druid::getLanguageSwitcher();
        }

        return view("druid::{$view}", $context);
    }
}
