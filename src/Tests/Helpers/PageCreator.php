<?php

namespace Webid\Druid\Tests\Helpers;

use App\Enums\Langs;
use App\Models\Page;
use Webid\Druid\Database\Factories\PageFactory;

trait PageCreator
{
    protected function createPage(array $params = []): Page
    {
        /** @var Page $page */
        $page = PageFactory::new()->create($params);

        return $page;
    }

    protected function createPageInEnglish(array $params = []): Page
    {
        /** @var Page $page */
        $page = PageFactory::new()->create([...$params, 'lang' => Langs::EN->value]);

        return $page;
    }

    protected function createFrenchTranslationPage(array $params = [], ?Page $fromPage = null): Page
    {
        /** @var Page $page */
        $page = PageFactory::new()->create([...$params, 'lang' => Langs::FR->value, 'translation_origin_page_id' => $fromPage->getKey()]);

        return $page;
    }

    protected function createGermanTranslationPage(array $params = [], ?Page $fromPage = null): Page
    {
        /** @var Page $page */
        $page = PageFactory::new()->create([...$params, 'lang' => Langs::DE->value, 'translation_origin_page_id' => $fromPage->getKey()]);

        return $page;
    }
}
