<?php

declare(strict_types=1);

namespace Webid\Druid\database\seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ReusableComponentsSeeder::class,
            PostsSeeder::class,
            PagesSeeder::class,
            MenusSeeder::class,
        ]);
    }
}
