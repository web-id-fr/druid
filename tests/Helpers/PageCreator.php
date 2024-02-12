<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Models\Page;

trait PageCreator
{
    protected function createPage(array $params = []): Page
    {
        return Page::factory($params)->create();
    }

    protected function createPageInEnglish(array $params = []): Page
    {
        return Page::factory([...$params, 'lang' => Langs::EN->value])->create();
    }

    protected function createFrenchTranslationPage(array $params = [], ?Page $fromPage = null): Page
    {
        if ($fromPage) {
            $params['translation_origin_model_id'] = $fromPage->getKey();
        }

        return Page::factory([...$params, 'lang' => Langs::FR->value])->create();
    }

    protected function createGermanTranslationPage(array $params = [], ?Page $fromPage = null): Page
    {
        if ($fromPage) {
            $params['translation_origin_model_id'] = $fromPage->getKey();
        }

        return Page::factory([...$params, 'lang' => Langs::DE->value])->create();
    }
}
