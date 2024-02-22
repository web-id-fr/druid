<?php

namespace Webid\Druid\App\Filament\Pages\SettingsPage;

use Filament\Forms\Form;

interface SettingsInterface
{
    public static function formSchema(Form $form): Form;
}
