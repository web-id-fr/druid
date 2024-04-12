<?php

declare(strict_types=1);

namespace Webid\Druid\database\seeders;

use Illuminate\Database\Seeder;
use Webid\Druid\Database\Factories\ReusableComponentFactory;

class ReusableComponentsSeeder extends Seeder
{
    public function run(): void
    {
        if (isMultilingualEnabled()) {
            foreach (getLocales() as $localKey => $locale) {
                ReusableComponentFactory::new()->count(2)->create(['lang' => $localKey]);
            }

            return;
        }

        ReusableComponentFactory::new()->count(2)->create();
    }
}
