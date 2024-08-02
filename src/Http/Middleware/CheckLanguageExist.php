<?php

declare(strict_types=1);

namespace Webid\Druid\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Webid\Druid\Facades\Druid;

class CheckLanguageExist
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $lang = $request->route('lang');

        if (! array_key_exists($lang, Druid::getLocales())) {
            abort(404);
        }

        return $next($request);
    }
}
