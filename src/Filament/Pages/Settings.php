<?php

namespace Webid\Druid\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Settings as SettingsModel;

/**
 * @property Form $form
 */
class Settings extends Page
{
    use InteractsWithFormActions;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'druid::filament.pages.settings';

    protected static ?int $navigationSort = 4;

    public function form(Form $form): Form
    {
        return Druid::settingsPage()::formSchema($form)
            ->statePath('data');
    }

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        /** @var array<string, mixed> $data */
        $data = SettingsModel::get();

        $this->callHook('beforeFill');

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    public function save(): void
    {
        try {
            $this->callHook('beforeValidate');

            $data = collect($this->form->getState())->all();

            $this->callHook('afterValidate');

            $this->callHook('beforeSave');

            foreach ($data as $key => $value) {
                SettingsModel::set($key, $value);
            }

            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }

        $this->getSavedNotification()->send();
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save settings'))
                ->submit('save'),
        ];
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('Settings saved successfully.'));
    }

    public static function canAccess(): bool
    {
        return Druid::isSettingsPageEnabled();
    }
}
