<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\Database\Factories\PageFactory;
use Webid\Druid\Models\Page;

trait PageCreator
{
    protected function createPage(array $params = []): Page
    {
        /** @var Page $page */
        $page = PageFactory::new()->create($params);

        return $page;
    }

    protected function createDraftPage(array $params = []): Page
    {
        /** @var Page $page */
        $page = PageFactory::new()
            ->draft()
            ->create($params);

        return $page;
    }

    protected function createPageInEnglish(array $params = []): Page
    {
        /** @var Page $page */
        $page = PageFactory::new()->create([...$params, 'lang' => 'en']);

        return $page;
    }

    protected function createFrenchTranslationPage(array $params = [], ?Page $fromPage = null): Page
    {
        if ($fromPage) {
            $params['translation_origin_model_id'] = $fromPage->getKey();
        }

        /** @var Page $page */
        $page = PageFactory::new()->create([...$params, 'lang' => 'fr']);

        return $page;
    }

    protected function createGermanTranslationPage(array $params = [], ?Page $fromPage = null): Page
    {
        if ($fromPage) {
            $params['translation_origin_model_id'] = $fromPage->getKey();
        }

        /** @var Page $page */
        $page = PageFactory::new()->create([...$params, 'lang' => 'de']);

        return $page;
    }
}
