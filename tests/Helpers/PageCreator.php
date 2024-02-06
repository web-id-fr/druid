<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Models\Page as Page;
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
        if ($fromPage) {
            $params['translation_origin_model_id'] = $fromPage->getKey();
        }

        /** @var Page $page */
        $page = PageFactory::new()->create([...$params, 'lang' => Langs::FR->value]);

        return $page;
    }

    protected function createGermanTranslationPage(array $params = [], ?Page $fromPage = null): Page
    {
        if ($fromPage) {
            $params['translation_origin_model_id'] = $fromPage->getKey();
        }

        /** @var Page $page */
        $page = PageFactory::new()->create([...$params, 'lang' => Langs::DE->value]);

        return $page;
    }
}
