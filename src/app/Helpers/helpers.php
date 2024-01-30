<?php

if (! function_exists('package_base_path')) {
    /**
     * Retourne le chemin en partant de la racine du package
     */
    function package_base_path(string $path = ''): string
    {
        $path = ltrim($path, '/');

        return __DIR__."/../../{$path}";
    }
}
