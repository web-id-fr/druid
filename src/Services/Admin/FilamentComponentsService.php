<?php

namespace Webid\Druid\Services\Admin;

use Filament\Forms\Components\Builder;
use Webid\Druid\Components\ComponentInterface;

class FilamentComponentsService
{
    public function getFlexibleContentFields(): Builder
    {
        $blocks = [];

        foreach (config('cms.components') as $component) {
            /** @var ComponentInterface $componentObject */
            $componentObject = new $component['class'];

            $blocks[] =
                Builder\Block::make($componentObject->fieldName())
                    ->schema($componentObject->blockSchema());
        }

        return Builder::make('content')
            ->blocks($blocks)
            ->blockNumbers(false)
            ->addActionLabel(__('Add a component'));
    }
}
