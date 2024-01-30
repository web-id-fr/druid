<?php

declare(strict_types=1);

namespace Webid\Druid\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MultilingualFeatureForbidden
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (isMultilingualEnabled()) {
            abort(404);
        }

        return $next($request);
    }
}
