<?php

namespace App\Http\Middleware;

use App\Support\Seo\SeoRobots;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddSeoRobotsHeader
{
    /**
     * @param Closure(Request): Response $next
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $response = $next($request);

        if (SeoRobots::shouldSendHeader()) {
            $response->headers->set(
                'X-Robots-Tag',
                SeoRobots::content()
            );
        }

        return $response;
    }
}
