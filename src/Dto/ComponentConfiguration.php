<?php

namespace Webid\Druid\Dto;

use Webid\Druid\Components\ComponentInterface;
use Webid\Druid\Exceptions\ClassNotFoundException;

class ComponentConfiguration
{
    private function __construct(
        readonly public string $type,
        readonly public string $class,
        readonly public array $disabledFor,
    ) {
    }

    public static function fromArray(array $array): self
    {
        if (! isset($array['class'])) {
            throw new ClassNotFoundException();
        }

        /** @var ComponentInterface $componentClass */
        $componentClass = $array['class'];

        return new self(
            $componentClass::fieldName(),
            $array['class'],
            $array['disabled_for'] ?? [],
        );
    }
}
