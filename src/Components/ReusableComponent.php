<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Illuminate\Contracts\View\View;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\ReusableComponent as ReusableComponentModel;
use Webid\Druid\Repositories\ReusableComponentsRepository;
use Webid\Druid\Services\ComponentDisplayContentExtractor;
use Webmozart\Assert\Assert;

class ReusableComponent implements ComponentInterface
{
    /**
     * @return array<int, Select>
     */
    public static function blockSchema(): array
    {
        /** @var ReusableComponentsRepository $reusableComponentsRepository */
        $reusableComponentsRepository = app(ReusableComponentsRepository::class);

        return [
            Select::make('reusable_component')
                ->label(__('Reusable component'))
                ->placeholder(__('Select a component'))
                ->options(function (Get $get) use ($reusableComponentsRepository) {
                    $lang = $get('../../../lang') ?? Druid::getDefaultLocaleKey();
                    Assert::string($lang);

                    return $reusableComponentsRepository->allForLang(Langs::from($lang))
                        // @phpstan-ignore-next-line
                        ->mapWithKeys(fn (ReusableComponentModel $reusableComponent) => [
                            $reusableComponent->getKey() => $reusableComponent->title,
                        ]);
                })
                ->searchable(),
        ];
    }

    public static function fieldName(): string
    {
        return 'reusable-component';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toBlade(array $data): View
    {
        $reusableComponent = self::getComponentFromData($data);
        /** @var ComponentDisplayContentExtractor $componentContentExtractor */
        $componentContentExtractor = app()->make(ComponentDisplayContentExtractor::class);

        return view('druid::components.text', [
            'content' => $componentContentExtractor->getContentFromBlocks($reusableComponent->content),
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toSearchableContent(array $data): string
    {
        $reusableComponent = self::getComponentFromData($data);

        foreach ($reusableComponent->content as $simpleBlock) {

        }

        return '';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function getComponentFromData(array $data): ReusableComponentModel
    {
        $componentID = $data['reusable_component'];
        Assert::string($componentID);

        /** @var ReusableComponentModel $reusableComponent */
        $reusableComponent = ReusableComponentModel::query()->findOrFail(intval($componentID));

        return $reusableComponent;
    }

    public static function imagePreview(): string
    {
        return '/vendor/druid/cms/images/components/reusable_component.png';
    }
}
