<?php

declare(strict_types=1);

if (! function_exists('isBlogDefaultRoutesEnable')) {
    function isBlogDefaultRoutesEnable(): bool
    {
        return config('cms.enable_default_blog_routes') === true;
    }
}

if (! function_exists('getPostsPerPage')) {
    function getPostsPerPage(): int
    {
        /** @var int $perPage */
        $perPage = config('cms.blog.posts_per_page');

        return $perPage;
    }
}
