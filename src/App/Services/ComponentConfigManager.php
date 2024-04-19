<?php

namespace Webid\Druid\App\Services;

use Illuminate\Support\Collection;
use Webid\Druid\App\Dto\ComponentConfiguration;
use Webid\Druid\App\Exceptions\ClassNotFoundException;
use Webmozart\Assert\Assert;

class ComponentConfigManager
{
    /**
     * @return Collection<ComponentConfiguration>
     *
     * @throws ClassNotFoundException
     */
    public function getComponentsConfiguration(): Collection
    {
        $componentCollection = collect();
        $components = config('cms.components');

        if (empty($components) || ! is_array($components)) {
            return $componentCollection;
        }

        foreach ($components as $componentConfig) {
            $componentCollection->push(ComponentConfiguration::fromArray($componentConfig));
        }

        return $componentCollection;
    }

    /**
     * @throws ClassNotFoundException
     */
    public function getComponentsConfigurationFor(string $componentType): ComponentConfiguration
    {
        $componentsConfiguration = $this->getComponentsConfiguration();

        $configuration = $componentsConfiguration->where('type', $componentType)->firstOrFail();

        Assert::isInstanceOf($configuration, ComponentConfiguration::class);

        return $configuration;
    }
}
