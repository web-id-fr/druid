<?php

namespace Webid\Druid\Filament\Pages\SettingsPage;

use Filament\Forms\Form;
use Webid\Druid\Services\Admin\FilamentFieldsBuilders\FilamentSettingsFieldsBuilder;

class SettingsForm implements SettingsInterface
{
    public static function formSchema(Form $form): Form
    {
        /** @var FilamentSettingsFieldsBuilder $fieldsBuilder */
        $fieldsBuilder = app()->make(FilamentSettingsFieldsBuilder::class);

        return $form->schema($fieldsBuilder->getFields());
    }
}
