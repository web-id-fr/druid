<?php

namespace Webid\Druid\Dto;

use Webid\Druid\Components\ComponentInterface;
use Webid\Druid\Exceptions\ClassNotFoundException;

class ComponentConfiguration
{
    /**
     * @param array<int, string> $disabledFor
     */
    private function __construct(
        readonly public string $type,
        readonly public string $class,
        readonly public array $disabledFor,
    ) {
    }

    /**
     * @param array<string, mixed> $array
     * @throws ClassNotFoundException
     */
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
