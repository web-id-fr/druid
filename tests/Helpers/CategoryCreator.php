<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\Database\Factories\CategoryFactory;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Models\Category;

trait CategoryCreator
{
    public function createFrenchCategory(array $params = []): Category
    {
        /** @var Category $category */
        $category = CategoryFactory::new()->create([...$params, 'lang' => Langs::FR->value]);

        return $category;
    }
}
