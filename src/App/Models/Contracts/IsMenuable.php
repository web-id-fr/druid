<?php

declare(strict_types=1);

namespace Webid\Druid\App\Models\Contracts;

/**
 * @property string $title
 */
interface IsMenuable
{
    public function getMenuLabel(): string;

    public function fullUrlPath(): string;
}
