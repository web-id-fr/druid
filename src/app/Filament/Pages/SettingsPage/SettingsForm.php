<?php

namespace Webid\Druid\App\Filament\Pages\SettingsPage;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class SettingsForm implements SettingsInterface
{
    public static function formSchema(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make(__('General'))
                        ->schema([
                            TextInput::make('site_name')
                                ->label(__('Name of the site'))
                                ->required(),
                            TextInput::make('site_description')
                                ->label(__('Description of the site'))
                                ->required(),
                            TextInput::make('site_email')
                                ->label(__('Email of the site'))
                                ->required(),
                        ]),
                    Tabs\Tab::make('Application')
                        ->schema([
                            TextInput::make('app_version')
                                ->label(__('Version of the application'))
                                ->required(),
                        ]),
                ]),
        ]);
    }
}
