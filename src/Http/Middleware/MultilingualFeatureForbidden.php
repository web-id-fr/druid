<?php

declare(strict_types=1);

namespace Webid\Druid\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Webid\Druid\Facades\Druid;

class MultilingualFeatureForbidden
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (Druid::isMultilingualEnabled()) {
            abort(404);
        }

        return $next($request);
    }
}
