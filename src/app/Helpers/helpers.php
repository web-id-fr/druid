<?php

if (!function_exists('current_url_is')) {
    /**
     * Compare la chaine passée en paramètre avec l'url actuelle,
     * pour déterminer si la chaine correspond à la page actuelle
     *
     * @param string $urlToCompare
     *
     * @return bool
     */
    function current_url_is(string $urlToCompare): bool
    {
        /** @var string $urlPath */
        $urlPath = parse_url($urlToCompare, PHP_URL_PATH) ?? '';
        $urlPath = trim($urlPath, '/');

        return request()->is("$urlPath*");
    }
}


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
