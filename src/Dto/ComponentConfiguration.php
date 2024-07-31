<?php

namespace Webid\Druid\Dto;

use Webid\Druid\Components\ComponentInterface;
use Webid\Druid\Exceptions\ClassNotFoundException;

class ComponentConfiguration
{
    /**
     * @param  array<int, string>  $disabledFor
     */
    private function __construct(
        readonly public string $type,
        readonly public string $class,
        readonly public array $disabledFor,
    ) {
    }

    /**
     * @param  array<string, mixed>  $array
     *
     * @throws ClassNotFoundException
     */
    public static function fromArray(array $array): self
    {
        if (! isset($array['class'])) {
            throw new ClassNotFoundException;
        }

        /** @var string $className */
        $className = $array['class'];

        /** @var ComponentInterface $componentClass */
        $componentClass = new $className;

        return new self(
            $componentClass::fieldName(),
            $className,
            isset($array['disabled_for']) && is_array($array['disabled_for']) ? $array['disabled_for'] : [],
        );
    }
}
