<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $locales = config('localization.supported', []);

        return response()
            ->view('seo.sitemap-index', [
                'locales' => $locales,
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function locale(string $locale): Response
    {
        $supportedLocales = config('localization.supported', []);

        abort_unless(
            in_array($locale, $supportedLocales, true),
            404
        );

        $routeNames = config('seo.sitemap.routes', []);

        $urls = collect($routeNames)
            ->filter(
                fn (mixed $routeName): bool =>
                    is_string($routeName)
                    && $routeName !== ''
            )
            ->map(function (string $routeName) use (
                $locale,
                $supportedLocales
            ): array {
                $alternates = collect($supportedLocales)
                    ->mapWithKeys(
                        fn (string $alternateLocale): array => [
                            $alternateLocale => URL::route(
                                $routeName,
                                ['locale' => $alternateLocale]
                            ),
                        ]
                    )
                    ->all();

                return [
                    'location' => $alternates[$locale],
                    'alternates' => $alternates,
                    'x_default' => $alternates[
                        config('localization.default', 'de')
                    ] ?? null,
                ];
            })
            ->values()
            ->all();

        return response()
            ->view('seo.sitemap-locale', [
                'locale' => $locale,
                'urls' => $urls,
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
