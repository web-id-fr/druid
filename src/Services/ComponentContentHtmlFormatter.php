<?php

namespace Webid\Druid\Services;

class ComponentContentHtmlFormatter
{
    public function __construct(private readonly ComponentConfigManager $componentConfigManager)
    {

    }

    /**
     * @param  array[]  $contentBlocks
     */
    public function convertToHtml(array $contentBlocks): string
    {
        $html = '';
        foreach ($contentBlocks as $contentBlock) {
            $componentConfiguration = $this->componentConfigManager->getComponentsConfigurationFor($contentBlock['type']);

            $html .= call_user_func([$componentConfiguration->class, 'toHtml'], $contentBlock['data']);
            $html .= PHP_EOL;
        }

        return $html;
    }
}
