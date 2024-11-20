<?php

declare(strict_types=1);

namespace Webid\Druid\App\Console\Commands;

use Illuminate\Console\Command;
use Webid\Druid\App\Enums\Langs;

class InstallationCommand extends Command
{
    /** @var string */
    protected $signature = 'druid:install';

    /** @var string */
    protected $description = 'Multisteps installer for Dru^ID CMS';

    private const NO = 'No';

    private const YES = 'Yes';

    public function handle(): int
    {
        $this->info('Installing Dru^ID');

        $this->info('Running php artisan migrate');
        $this->call('migrate');

        $filamentInstalled = $this->choice('Already have a Filament admin setup on this project with the Curator addon?', [self::YES, self::NO], 1);
        if ($filamentInstalled === self::NO) {
            $this->error('Please first install Filament and Curator as explain in the documentation');
            return self::SUCCESS;
        }

        $installRoutingAndControllers = $this->choice('Install a basic route/controller system for the CMS?', [self::YES, self::NO], 0);
        if ($installRoutingAndControllers === self::YES) {

        }

        $enableMultilingualFeature = $this->choice('Enable the multilingual feature?', [self::YES, self::NO], 1);
        if ($enableMultilingualFeature === self::YES) {
            $languages = $this->choice('Which (comma separated) languages? You can add more later', collect(Langs::cases())->mapWithKeys(fn ($item) => [$item->value => $item->getName()])->toArray(), multiple: true);

            $mainLanguage = $this->choice('Which languages is the main one', $languages);
        }

        $runSeeder = $this->choice('Seed demo data?', [self::YES, self::NO], 0);
        if ($runSeeder === self::YES) {

        }

        return self::SUCCESS;
    }
}
