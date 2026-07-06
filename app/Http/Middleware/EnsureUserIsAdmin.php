<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isActive() || ! $request->user()->isAdmin()) {
            abort(403, 'Kein Zugriff auf den Adminbereich.');
        }

        return $next($request);
    }
}
