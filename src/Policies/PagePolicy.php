<?php

declare(strict_types=1);

namespace Webid\Druid\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Webid\Druid\Models\Page;

class PagePolicy
{
    use HandlesAuthorization;

    public function view(?Authenticatable $user, Page $page): bool
    {
        return $page->isPublished() || $user;
    }
}
