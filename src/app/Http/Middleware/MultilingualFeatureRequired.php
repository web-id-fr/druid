<?php

declare(strict_types=1);

namespace Webid\Druid\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Webid\Druid\App\Facades\Druid;

class MultilingualFeatureRequired
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (! Druid::isMultilingualEnabled()) {
            abort(404);
        }

        return $next($request);
    }
}
