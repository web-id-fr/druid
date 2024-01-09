<?php

namespace Webid\Druid\Services;

use Webmozart\Assert\Assert;

class ComponentSearchContentExtractor
{
    public function __construct(private readonly ComponentConfigManager $componentConfigManager)
    {

    }

    /**
     * @param  array<int, array<mixed>>  $contentBlocks
     */
    public function extractSearchableContentFromBlocks(array $contentBlocks): string
    {
        $html = '';
        foreach ($contentBlocks as $contentBlock) {
            $blockType = $contentBlock['type'];
            Assert::string($blockType);

            $componentConfiguration = $this->componentConfigManager->getComponentsConfigurationFor($blockType);

            // @phpstan-ignore-next-line
            $html .= call_user_func([$componentConfiguration->class, 'toSearchableContent'], $contentBlock['data']);
            $html .= PHP_EOL;
        }

        return $html;
    }
}
