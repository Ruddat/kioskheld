<?php

namespace App\Support;

use Illuminate\Support\Str;

class ShopUrl
{
    public static function citySlug(array $shop): string
    {
        $city = $shop['city']
            ?? $shop['location']['city']
            ?? $shop['delivery_location']['city']
            ?? null;

        if (blank($city)) {
            return 'deine-naehe';
        }

        return Str::slug((string) $city);
    }

    public static function shopSlugWithId(array $shop): string
    {
        $slug = $shop['slug'] ?? Str::slug($shop['name'] ?? 'kiosk');
        $id = $shop['id'] ?? null;

        if ($id) {
            return $slug . '-' . $id;
        }

        return $slug;
    }

    public static function apiSlugFromUrlSlug(string $shopSlugWithId): string
    {
        return preg_replace('/-\d+$/', '', $shopSlugWithId) ?: $shopSlugWithId;
    }
}
