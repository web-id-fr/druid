<?php

namespace Webid\Druid\Services;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Webid\Druid\Enums\PageStatus;
use Webid\Druid\Enums\PostStatus;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Filament\Resources\CommonFields;
use Webid\Druid\Models\Page;
use Webid\Druid\Models\Post;
use Webid\Druid\Repositories\PageRepository;
use Webid\Druid\Repositories\PostRepository;
use Webid\Druid\Services\Admin\FilamentComponentsService;
use Webmozart\Assert\Assert;

class DefaultFilamentFieldsProvider
{
    /**
     * @return array<string, Component>
     */
    public function getDefaultSettingsFields(): array
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

    /**
     * @return array<string, Component>
     */
    public function getDefaultPagesFields(): array
    {
        /** @var FilamentComponentsService $filamentComponentService */
        $filamentComponentService = app(FilamentComponentsService::class);

        /** @var PageRepository $pageRepository */
        $pageRepository = app(PageRepository::class);

        $contentTab = [
            'title' => TextInput::make('title')
                ->label(__('Title'))
                ->live(onBlur: true)
                ->afterStateUpdated(
                    fn (string $operation, $state, Set $set) => $operation === 'create'
                        ? $set('slug', Str::slug($state)) : null
                )
                ->required(),
            $filamentComponentService->getFlexibleContentFieldsForModel(Page::class),
        ];

        $parametersTab = [
            'slug' => TextInput::make('slug')
                ->label(__('Slug')),
            'parent_page_id' => Select::make('parent_page_id')
                ->label(__('Parent page'))
                ->placeholder(__('Select a parent page'))
                // @phpstan-ignore-next-line
                ->options(fn (?Model $record): Collection => $pageRepository->allExceptForPageId($record?->getKey())->pluck('title', 'id'))
                ->searchable(),
            'status' => Select::make('status')
                ->label(__('Status'))
                ->placeholder(__('Select a status'))
                ->options(PageStatus::class)
                ->default(PageStatus::PUBLISHED)
                ->required(),
            'published_at' => DatePicker::make('published_at')
                ->label(__('Published at'))
                ->default(now())
                ->required(),
        ];

        if (Druid::isMultilingualEnabled()) {
            $parametersTab = array_merge(
                $parametersTab,
                [
                    'lang' => Select::make('lang')
                        ->label(__('Language'))
                        ->options(
                            collect(Druid::getLocales())->mapWithKeys(fn ($item, $key) => [$key => $item['label'] ?? __('No label')])
                        )
                        ->live()
                        ->placeholder(__('Select a language')),
                    'translation_origin_model_id' => Select::make('translation_origin_model_id')
                        ->label(__('Translation origin model'))
                        ->placeholder(__('Is a translation of...'))
                        ->options(function (Get $get, ?Page $page) use ($pageRepository) {
                            $lang = $get('lang');
                            Assert::string($lang);

                            $allDefaultLanguagePages = $pageRepository->allFromDefaultLanguageWithoutTranslationForLang($lang)
                                // @phpstan-ignore-next-line
                                ->mapWithKeys(fn (Page $mapPage) => [$mapPage->getKey() => $mapPage->title]);

                            if ($page) {
                                $allDefaultLanguagePages->put($page->id, __('#No origin model'));
                            }

                            if ($page?->translationOriginModel?->isNot($page)) {
                                $allDefaultLanguagePages->put($page->translationOriginModel->id, $page->translationOriginModel->title);
                            }

                            return $allDefaultLanguagePages;
                        })
                        ->searchable()
                        ->hidden(fn (Get $get): bool => ! $get('lang') || $get('lang') === Druid::getDefaultLocaleKey())
                        ->live(),
                ]
            );
        }

        return [
            'tabs' => Tabs::make('Tabs')
                ->tabs([
                    'content' => Tabs\Tab::make(__('Content'))->schema($contentTab),
                    'parameters' => Tabs\Tab::make(__('Parameters'))->schema($parametersTab)->columns(2),
                    'seo' => Tabs\Tab::make(__('SEO'))->schema(CommonFields::getCommonSeoFields())->columns(2),
                ])
                ->activeTab(1)
                ->columnSpanFull(),
        ];
    }

    /**
     * @return array<string, Component>
     */
    public function getDefaultPostsFields(): array
    {
        /** @var FilamentComponentsService $filamentComponentService */
        $filamentComponentService = app(FilamentComponentsService::class);

        /** @var PostRepository $postsRepository */
        $postsRepository = app(PostRepository::class);

        $contentTab = [
            'title' => TextInput::make('title')
                ->label(__('Title'))
                ->live(onBlur: true)
                ->afterStateUpdated(
                    fn (string $operation, $state, Set $set) => $operation === 'create'
                        ? $set('slug', Str::slug($state)) : null
                )
                ->required(),
            'excerpt' => RichEditor::make('excerpt')
                ->label(__('Excerpt')),
            $filamentComponentService->getFlexibleContentFieldsForModel(Post::class),
        ];

        $parametersTab = [
            'thumbnail_id' => CuratorPicker::make('thumbnail_id')
                ->label(__('Image'))
                ->preserveFilenames()
                ->columnSpanFull(),
            'thumbnail_alt' => TextInput::make('thumbnail_alt')
                ->label(__('Image alt'))
                ->columnSpanFull(),
            'status' => Select::make('status')
                ->label(__('Status'))
                ->options(PostStatus::class)
                ->default(PostStatus::PUBLISHED)
                ->required(),
            'published_at' => DatePicker::make('published_at')
                ->label(__('Published at'))
                ->default(now())
                ->required(),
            'slug' => TextInput::make('slug')
                ->label(__('Slug'))
                ->required(),
            'is_top_article' => Toggle::make('is_top_article')
                ->label(__('Top article'))
                ->helperText(__('Display this article in the top article section')),
        ];

        if (Druid::isMultilingualEnabled()) {
            $parametersTab = array_merge(
                $parametersTab,
                [
                    'lang' => Select::make('lang')
                        ->label(__('Language'))
                        ->options(
                            collect(Druid::getLocales())->mapWithKeys(fn ($item, $key) => [$key => $item['label'] ?? __('No label')])
                        )
                        ->live()
                        ->placeholder(__('Select a language')),
                    'translation_origin_model_id' => Select::make('translation_origin_model_id')
                        ->label(__('Translation origin model'))
                        ->placeholder(__('Is a translation of...'))
                        ->options(function (Get $get, ?Post $post) use ($postsRepository) {
                            $lang = $get('lang');
                            Assert::string($lang);

                            $allDefaultLanguagePosts = $postsRepository->allFromDefaultLanguageWithoutTranslationForLang($lang)
                                // @phpstan-ignore-next-line
                                ->mapWithKeys(fn (Post $mapPost) => [$mapPost->getKey() => $mapPost->title]);

                            if ($post) {
                                $allDefaultLanguagePosts->put($post->id, __('#No origin model'));
                            }

                            if ($post?->translationOriginModel?->isNot($post)) {
                                $allDefaultLanguagePosts->put($post->translationOriginModel->id, $post->translationOriginModel->title);
                            }

                            return $allDefaultLanguagePosts;
                        })
                        ->searchable()
                        ->hidden(fn (Get $get): bool => ! $get('lang') || $get('lang') === Druid::getDefaultLocaleKey())
                        ->live(),
                ]
            );
        }

        return [
            'tabs' => Tabs::make('Tabs')
                ->tabs([
                    'content' => Tabs\Tab::make(__('Content'))->schema($contentTab),
                    'parameters' => Tabs\Tab::make(__('Parameters'))->schema($parametersTab)->columns(2),
                    'seo' => Tabs\Tab::make(__('SEO'))->schema(CommonFields::getCommonSeoFields())->columns(2),
                ])->columnSpanFull(),
        ];
    }
}
