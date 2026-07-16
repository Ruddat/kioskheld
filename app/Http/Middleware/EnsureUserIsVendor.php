<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVendor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isActive() || ! $request->user()->isVendorUser()) {
            abort(403, 'Kein Zugriff auf das Vendor-Portal.');
        }

        return $next($request);
    }
}
