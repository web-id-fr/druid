<?php

namespace Webid\Druid\Services;

use Illuminate\Support\Collection;
use Webid\Druid\Dto\ComponentConfiguration;
use Webid\Druid\Exceptions\ClassNotFoundException;
use Webmozart\Assert\Assert;

class ComponentConfigManager
{
    /**
     * @return Collection<(int|string), mixed>
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

        /** @var array<string, mixed> $componentConfig */
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
