<?php

namespace Webid\Druid\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Webid\Druid\Enums\PostStatus;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Post;
use Webid\Druid\Repositories\PostRepository;
use Webid\Druid\Services\Admin\FilamentFieldsBuilders\FilamentPostFieldsBuilder;

class PostResource extends Resource
{
    public static function getModel(): string
    {
        return Druid::getModel('post');
    }

    protected static ?string $modelLabel = 'Post';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        /** @var FilamentPostFieldsBuilder $fieldsBuilder */
        $fieldsBuilder = app()->make(FilamentPostFieldsBuilder::class);

        return $form->schema(components: $fieldsBuilder->getFields());
    }

    public static function table(Table $table): Table
    {
        /** @var PostRepository $postRepository */
        $postRepository = app()->make(PostRepository::class);

        $columns = [
            Tables\Columns\TextColumn::make('title')
                ->label(__('Title'))
                ->color('primary')
                ->url(
                    url: fn (Post $record) => url($record->loadMissing('categories')->fullUrlPath()),
                    shouldOpenInNewTab: true
                )
                ->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->colors([
                    'success' => PostStatus::PUBLISHED,
                    'warning' => PostStatus::DRAFT,
                    'danger' => PostStatus::ARCHIVED,
                ])
                ->label(__('Status')),
            Tables\Columns\IconColumn::make('is_top_article')
                ->label(__('Top article'))
                ->boolean(),
            Tables\Columns\IconColumn::make('indexation')
                ->label(__('Indexation'))
                ->boolean(),
            Tables\Columns\IconColumn::make('follow')
                ->label(__('Follow'))
                ->boolean(),
            Tables\Columns\TextColumn::make('published_at')
                ->label(__('Published at'))
                ->dateTime()
                ->sortable(),
        ];

        if (Druid::isMultilingualEnabled()) {
            $columns[] = Tables\Columns\ViewColumn::make('translations')->view('druid::admin.post.translations');
        }

        return $table
            ->columns($columns)
            ->defaultSort('published_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()->button()->outlined()->icon(''),
                Tables\Actions\DeleteAction::make(),
                Action::make('replicate')
                    ->label(__('Replicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->action(fn (Post $record) => $postRepository->replicate($record))
                    ->requiresConfirmation()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->selectCurrentPageOnly()
            ->striped()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index' => PostResource\Pages\ListPosts::route('/'),
            'create' => PostResource\Pages\CreatePost::route('/create'),
            'view' => PostResource\Pages\ViewPost::route('/{record}'),
            'edit' => PostResource\Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return Druid::isBlogModuleEnabled();
    }
}
