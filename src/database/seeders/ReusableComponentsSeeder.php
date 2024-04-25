<?php

declare(strict_types=1);

namespace Webid\Druid\database\seeders;

use Illuminate\Database\Seeder;
use Webid\Druid\Database\Factories\ReusableComponentFactory;
use Webid\Druid\Facades\Druid;

class ReusableComponentsSeeder extends Seeder
{
    public function run(): void
    {
        if (Druid::isMultilingualEnabled()) {
            foreach (Druid::getLocales() as $localKey => $locale) {
                ReusableComponentFactory::new()->count(2)->create(['lang' => $localKey]);
            }

            return;
        }

        ReusableComponentFactory::new()->count(2)->create();
    }
}
