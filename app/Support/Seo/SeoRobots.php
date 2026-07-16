<?php

namespace App\Support\Seo;

use Illuminate\Support\Facades\Route;

final class SeoRobots
{
    public const INDEX_FOLLOW = 'index, follow';

    public const NOINDEX_FOLLOW = 'noindex, follow';

    public const NOINDEX_NOFOLLOW = 'noindex, nofollow';

    public static function content(): string
    {
        $routeName = Route::currentRouteName();

        if (! is_string($routeName) || $routeName === '') {
            return self::NOINDEX_NOFOLLOW;
        }

        if (self::matches(
            $routeName,
            config('seo.robots.noindex_nofollow', [])
        )) {
            return self::NOINDEX_NOFOLLOW;
        }

        if (self::matches(
            $routeName,
            config('seo.robots.noindex_follow', [])
        )) {
            return self::NOINDEX_FOLLOW;
        }

        return config(
            'seo.defaults.robots',
            self::INDEX_FOLLOW
        );
    }

    public static function shouldSendHeader(): bool
    {
        return str_starts_with(
            self::content(),
            'noindex'
        );
    }

    /**
     * @param array<int, string> $patterns
     */
    private static function matches(
        string $routeName,
        array $patterns
    ): bool {
        foreach ($patterns as $pattern) {
            if (self::routeNameMatches($routeName, $pattern)) {
                return true;
            }
        }

        return false;
    }

    private static function routeNameMatches(
        string $routeName,
        string $pattern
    ): bool {
        if ($routeName === $pattern) {
            return true;
        }

        if (! str_ends_with($pattern, '*')) {
            return false;
        }

        $prefix = substr($pattern, 0, -1);

        return str_starts_with($routeName, $prefix);
    }
}
