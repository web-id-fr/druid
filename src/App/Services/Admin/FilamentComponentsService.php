<?php

namespace Webid\Druid\App\Services\Admin;

use Filament\Forms\Components\Builder;
use Webid\Druid\App\Components\ComponentInterface;
use Webmozart\Assert\Assert;

class FilamentComponentsService
{
    public function getFlexibleContentFieldsForModel(string $modelClassName): Builder
    {
        $blocks = [];

        $componentsConfig = config('cms.components');
        Assert::isArray($componentsConfig);

        foreach ($componentsConfig as $component) {
            /** @var ComponentInterface $componentClass */
            $componentClass = $component['class'];

            if (
                isset($component['disabled_for']) &&
                is_array($component['disabled_for']) &&
                in_array($modelClassName, $component['disabled_for'], true)
            ) {
                continue;
            }

            $blocks[] =
                Builder\Block::make($componentClass::fieldName())
                    // @phpstan-ignore-next-line
                    ->schema($componentClass::blockSchema());
        }

        return Builder::make('content')
            ->blocks($blocks)
            ->blockNumbers(false)
            ->addActionLabel(__('Add a component'))
            ->columnSpanFull();
    }
}
