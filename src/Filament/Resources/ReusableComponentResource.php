<?php

namespace Webid\Druid\Filament\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webid\Druid\Models\ReusableComponent;
use Webid\Druid\Services\Admin\FilamentComponentsService;

class ReusableComponentResource extends Resource
{
    protected static ?string $model = ReusableComponent::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Pages';

    public static function form(Form $form): Form
    {
        /** @var FilamentComponentsService $filamentComponentService */
        $filamentComponentService = app(FilamentComponentsService::class);

        return $form
            ->schema(components: [
                TextInput::make('title')
                    ->label(__('Title'))
                    ->required(),

                $filamentComponentService->getFlexibleContentFieldsForModel(ReusableComponent::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Webid\Druid\Filament\Resources\ReusableComponentResource\Pages\ListReusableComponents::route('/'),
            'create' => \Webid\Druid\Filament\Resources\ReusableComponentResource\Pages\CreateReusableComponent::route('/create'),
            'view' => \Webid\Druid\Filament\Resources\ReusableComponentResource\Pages\ViewReusableComponent::route('/{record}'),
            'edit' => \Webid\Druid\Filament\Resources\ReusableComponentResource\Pages\EditReusableComponent::route('/{record}/edit'),
        ];
    }
}
