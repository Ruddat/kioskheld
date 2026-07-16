<?php

namespace App\Support\Seo;

use Illuminate\Routing\Route as IlluminateRoute;
use Illuminate\Support\Facades\URL;

final class SeoUrl
{
    public static function canonical(): string
    {
        return URL::current();
    }

    /**
     * @return array<string, string>
     */
    public static function alternates(): array
    {
        $route = request()->route();

        if (! $route instanceof IlluminateRoute) {
            return [];
        }

        $routeName = $route->getName();

        if (! is_string($routeName) || $routeName === '') {
            return [];
        }

        $parameters = self::publicRouteParameters($route);
        $alternates = [];

        foreach (config('localization.supported', []) as $locale) {
            try {
                $alternates[$locale] = URL::route($routeName, [
                    ...$parameters,
                    'locale' => $locale,
                ]);
            } catch (\Throwable) {
                return [];
            }
        }

        return $alternates;
    }

    public static function xDefault(): ?string
    {
        $defaultLocale = config('localization.default', 'de');

        return self::alternates()[$defaultLocale] ?? null;
    }

    public static function openGraphLocale(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return config("seo.locales.{$locale}", 'de_DE');
    }

    /**
     * Gibt nur echte URL-Parameter zurück.
     *
     * Route::view() hinterlegt intern unter anderem "view" und "status".
     * Diese Werte dürfen nicht als Query-String in SEO-URLs erscheinen.
     *
     * @return array<string, mixed>
     */
    private static function publicRouteParameters(
        IlluminateRoute $route
    ): array {
        preg_match_all(
            '/\{([^}]+)\}/',
            $route->uri(),
            $matches
        );

        $parameterNames = collect($matches[1] ?? [])
            ->map(
                fn (string $name): string => rtrim($name, '?')
            )
            ->reject(
                fn (string $name): bool => $name === 'locale'
            );

        return $parameterNames
            ->mapWithKeys(
                fn (string $name): array => [
                    $name => $route->parameter($name),
                ]
            )
            ->filter(
                fn (mixed $value): bool => $value !== null
            )
            ->all();
    }

public static function localizedUrl(string $locale): string
{
    return self::alternates()[$locale]
        ?? URL::to('/'.$locale);
}

}
