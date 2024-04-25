<?php

namespace Webid\Druid\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webid\Druid\database\seeders\DatabaseSeeder;

class DemoSeeder extends Command
{
    protected $signature = 'druid:demo';

    protected $description = 'Seed demo data';

    public function handle(): void
    {
        try {
            // @phpstan-ignore-next-line
            $firstUser = User::query()->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->call('make:filament-user', ['--name' => 'admin', '--email' => 'admin@test.com',  '--password' => 'password']);
        }

        $this->info('Start seeding data');

        $this->call('db:seed', ['--class' => DatabaseSeeder::class]);

        $this->info('Data successfully seeded');
    }
}
