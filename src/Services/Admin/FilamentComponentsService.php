<?php

namespace Webid\Druid\Services\Admin;

use Filament\Forms\Components\Builder;
use Webid\Druid\Components\ComponentInterface;

class FilamentComponentsService
{
    public function getFlexibleContentFieldsForModel(string $modelClassName): Builder
    {
        $blocks = [];

        foreach (config('cms.components') as $component) {
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
                    ->schema($componentClass::blockSchema());
        }

        return Builder::make('content')
            ->blocks($blocks)
            ->blockNumbers(false)
            ->addActionLabel(__('Add a component'))
            ->columnSpanFull();
    }
}
