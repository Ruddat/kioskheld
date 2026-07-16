<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = (string) $request->route('locale');

        $supportedLocales = config('localization.supported', [
            'de',
            'en',
            'tr',
        ]);

        if (! in_array($locale, $supportedLocales, true)) {
            abort(404);
        }

        App::setLocale($locale);

        URL::defaults([
            'locale' => $locale,
        ]);

        /*
         * Locale wird für die URL-Generierung als Default gespeichert,
         * aber nicht als Argument an bestehende Controller weitergereicht.
         */
        $request->route()?->forgetParameter('locale');

        return $next($request);
    }
}
