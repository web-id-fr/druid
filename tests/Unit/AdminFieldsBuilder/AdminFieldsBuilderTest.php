<?php

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Webid\Druid\Services\Admin\FilamentFieldsBuilders\FilamentSettingsFieldsBuilder;

beforeEach(function () {
    /** @var FilamentSettingsFieldsBuilder $builder */
    $this->builder = new FilamentSettingsFieldsBuilder;
    expect($this->builder->getFields())->toBeArray()->toBeEmpty();
});

test('that we can add a single field in the builder', function () {

    $this->builder->addField(
        TextInput::make('slug')
            ->label(__('Slug')),
        'slug',
    );

    expect($this->builder->getFields())->toBeArray()->toHaveCount(1);
    expect($this->builder->getFields()['slug'])->not->toBeEmpty();
});

test('that we can bulk update all fields', function () {
    $this->builder->addField(
        TextInput::make('slug')
            ->label(__('Slug')),
        'slug'
    );

    expect($this->builder->getFields())->toBeArray()->toHaveCount(1);
    expect($this->builder->getFields()['slug'])->not->toBeEmpty();

    $this->builder->updateFields([
        'field_1' => TextInput::make('field_1')
            ->label(__('Field 1')),
        'field_2' => TextInput::make('field_1')
            ->label(__('Field 1')),
    ]);

    expect($this->builder->getFields())->toBeArray()->toHaveCount(2);
    expect($this->builder->getFields()['field_1'])->not->toBeEmpty();
    expect($this->builder->getFields()['field_2'])->not->toBeEmpty();
});

test('that we can add a child field', function () {
    $this->builder->updateFields(getBasicFields());

    $generalTabChildComponents = $this->builder->getFields()['tabs']->getChildComponents()['general']->getChildComponents();
    expect($generalTabChildComponents)->toBeArray()->toHaveCount(3);

    $this->builder->addField(
        TextInput::make('new_field')
            ->label(__('New field')),
        'new_field',
        'tabs.general'
    );

    $generalTabChildComponents = $this->builder->getFields()['tabs']->getChildComponents()['general']->getChildComponents();
    expect($generalTabChildComponents)->toBeArray()->toHaveCount(4);
    expect($generalTabChildComponents['new_field'])->not->toBeEmpty();

});

test('that we can add a child field after a specific field', function () {
    $this->builder->updateFields(getBasicFields());

    $generalTabChildComponents = $this->builder->getFields()['tabs']->getChildComponents()['general']->getChildComponents();
    expect($generalTabChildComponents)->toBeArray()->toHaveCount(3);

    $this->builder->addField(
        TextInput::make('new_field')
            ->label(__('New field')),
        'new_field',
        'tabs.general',
        'site_email'
    );

    $generalTabChildComponents = $this->builder->getFields()['tabs']->getChildComponents()['general']->getChildComponents();
    expect($generalTabChildComponents)->toBeArray()->toHaveCount(4);
    expect($generalTabChildComponents['new_field'])->not->toBeEmpty();

    expect(array_keys($generalTabChildComponents))->toBe([
        'site_name',
        'site_description',
        'new_field',
        'site_email',
    ]);
});

test('that we can add a child field before a specific field', function () {
    $this->builder->updateFields(getBasicFields());

    $generalTabChildComponents = $this->builder->getFields()['tabs']->getChildComponents()['general']->getChildComponents();
    expect($generalTabChildComponents)->toBeArray()->toHaveCount(3);

    $this->builder->addField(
        TextInput::make('new_field')
            ->label(__('New field')),
        'new_field',
        'tabs.general',
        after: 'site_name'
    );

    $generalTabChildComponents = $this->builder->getFields()['tabs']->getChildComponents()['general']->getChildComponents();
    expect($generalTabChildComponents)->toBeArray()->toHaveCount(4);
    expect($generalTabChildComponents['new_field'])->not->toBeEmpty();

    expect(array_keys($generalTabChildComponents))->toBe([
        'site_name',
        'new_field',
        'site_description',
        'site_email',
    ]);
});

test('that we can remove a child field', function () {
    $this->builder->updateFields(getBasicFields());

    $generalTabChildComponents = $this->builder->getFields()['tabs']->getChildComponents()['general']->getChildComponents();
    expect($generalTabChildComponents)->toBeArray()->toHaveCount(3);

    $this->builder->removeField(
        'site_description',
        'tabs.general'
    );

    $generalTabChildComponents = $this->builder->getFields()['tabs']->getChildComponents()['general']->getChildComponents();
    expect($generalTabChildComponents)->toBeArray()->toHaveCount(2);
    expect($generalTabChildComponents['site_name'])->not->toBeEmpty();
    expect($generalTabChildComponents['site_email'])->not->toBeEmpty();
});

function getBasicFields(): array
{
    return [
        'tabs' => Tabs::make('Tabs')
            ->tabs([
                'general' => Tabs\Tab::make(__('General'))
                    ->schema([
                        'site_name' => TextInput::make('site_name')
                            ->label(__('Name of the site'))
                            ->required(),
                        'site_description' => TextInput::make('site_description')
                            ->label(__('Description of the site'))
                            ->required(),
                        'site_email' => TextInput::make('site_email')
                            ->label(__('Email of the site'))
                            ->required(),
                    ]),
                'application' => Tabs\Tab::make('Application')
                    ->schema([
                        'app_version' => TextInput::make('app_version')
                            ->label(__('Version of the application'))
                            ->required(),
                    ]),
            ]),
    ];
}
