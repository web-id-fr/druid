<?php

if (! function_exists('package_base_path')) {
    function package_base_path(string $path = ''): string
    {
        $path = ltrim($path, '/');

        return __DIR__."/../{$path}";
    }
}
