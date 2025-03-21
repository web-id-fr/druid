<?php

namespace Webid\Druid\Services;

use Webid\Druid\Exceptions\ClassNotFoundException;
use Webmozart\Assert\Assert;

class ComponentDisplayContentExtractor
{
    public function __construct(private readonly ComponentConfigManager $componentConfigManager) {}

    /**
     * @param  array<int, array<mixed>>  $contentBlocks
     *
     * @throws ClassNotFoundException
     */
    public function getContentFromBlocks(array $contentBlocks): string
    {
        $html = '';
        foreach ($contentBlocks as $contentBlock) {
            $blockType = $contentBlock['type'];
            Assert::string($blockType);

            $componentConfiguration = $this->componentConfigManager->getComponentsConfigurationFor($blockType);

            // @phpstan-ignore-next-line
            $html .= call_user_func([$componentConfiguration->class, 'toBlade'], $contentBlock['data']);
            $html .= PHP_EOL;
        }

        return $html;
    }
}
