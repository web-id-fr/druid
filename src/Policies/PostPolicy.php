<?php

declare(strict_types=1);

namespace Webid\Druid\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Webid\Druid\Models\Post;

class PostPolicy
{
    use HandlesAuthorization;

    public function view(?Authenticatable $user, Post $post): bool
    {
        return $post->isPublished() || ($user && $post->users->contains($user));
    }
}
