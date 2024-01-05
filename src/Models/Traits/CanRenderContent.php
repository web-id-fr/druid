<?php

namespace Webid\Druid\Models\Traits;

use Webid\Druid\Components\ComponentInterface;

trait CanRenderContent
{
    public function renderContent(): string
    {
        $components = $this->content;
        $html = '';

        if (! $components) {
            return $html;
        }

        foreach ($components as $component) {
            $componentType = $component['type'];
            $componentData = $component['data'];

            $componentClass = 'Webid\\Druid\\Components\\'.ucfirst($componentType).'Component';
            $customComponentClass = 'App\\CustomComponents\\'.ucfirst($componentType).'Component';

            if (class_exists($componentClass) && is_subclass_of($componentClass, ComponentInterface::class)) {
                $html .= $componentClass::toBlade($componentData)->toHtml();
            } elseif (class_exists($customComponentClass) && is_subclass_of($customComponentClass, ComponentInterface::class)) {
                $html .= $customComponentClass::toBlade($componentData)->toHtml();
            }
        }

        return $html;
    }
}
