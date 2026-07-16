<?php

namespace App\Services\Catalog;

use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CatalogPayloadMapper
{
    public function category(array $source): array
    {
        $externalId = $this->requiredString($source, ['id', 'external_id']);
        $name = $this->requiredString($source, ['name']);

        return [
            'external_id' => $externalId,
            'name' => $name,
            'slug_candidate' => $this->nullableString($source, ['slug'])
                ?: Str::slug($name),
            'description' => $this->nullableString($source, ['description']),
            'image_url' => $this->nullableString($source, ['image_url', 'image']),
            'product_count' => $this->integer($source, ['product_count'], 0),
            'is_active' => $this->boolean($source, ['is_active', 'is_available'], true),
            'source_updated_at' => $this->date($source, ['updated_at', 'source_updated_at']),
        ];
    }

    public function product(array $source): array
    {
        $externalId = $this->requiredString($source, ['id', 'external_id']);
        $name = $this->requiredString($source, ['name']);

        $category = Arr::get($source, 'category');
        $categoryExternalId = is_array($category)
            ? $this->nullableString($category, ['id', 'external_id'])
            : $this->nullableString($source, ['category_id', 'catalog_category_id']);

        return [
            'external_id' => $externalId,
            'category_external_id' => $categoryExternalId,
            'name' => $name,
            'slug_candidate' => $this->nullableString($source, ['slug'])
                ?: Str::slug($name),
            'short_description' => $this->nullableString(
                $source,
                ['short_description']
            ),
            'description' => $this->nullableString($source, ['description']),
            'image_url' => $this->nullableString($source, ['image_url', 'image']),
            'brand' => $this->nullableString($source, ['brand']),
            'gtin' => $this->nullableString($source, ['gtin', 'ean']),
            'lowest_price' => $this->decimal($source, ['lowest_price']),
            'currency' => strtoupper(
                $this->nullableString($source, ['currency']) ?: 'EUR'
            ),
            'active_shop_count' => $this->integer(
                $source,
                ['active_shop_count'],
                0
            ),
            'is_available' => $this->boolean(
                $source,
                ['is_available'],
                false
            ),
            'is_active' => $this->boolean($source, ['is_active'], true),
            'source_updated_at' => $this->date(
                $source,
                ['updated_at', 'source_updated_at']
            ),
            'variants' => $this->variants($source['variants'] ?? []),
        ];
    }

    private function variants(mixed $variants): array
    {
        if (! is_array($variants)) {
            return [];
        }

        return collect($variants)
            ->filter(fn ($variant) => is_array($variant))
            ->map(function (array $variant): array {
                return [
                    'external_id' => $this->requiredString(
                        $variant,
                        ['id', 'external_id']
                    ),
                    'name' => $this->requiredString($variant, ['name']),
                    'description' => $this->nullableString(
                        $variant,
                        ['description']
                    ),
                    'is_active' => $this->boolean(
                        $variant,
                        ['is_active', 'is_available'],
                        true
                    ),
                    'source_updated_at' => $this->date(
                        $variant,
                        ['updated_at', 'source_updated_at']
                    ),
                ];
            })
            ->values()
            ->all();
    }

    private function requiredString(array $source, array $keys): string
    {
        $value = $this->nullableString($source, $keys);

        if ($value === null) {
            throw new InvalidArgumentException(
                'Pflichtfeld fehlt: '.implode(' oder ', $keys)
            );
        }

        return $value;
    }

    private function nullableString(array $source, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = Arr::get($source, $key);

            if (is_string($value) || is_numeric($value)) {
                $value = trim((string) $value);

                if ($value !== '') {
                    return $value;
                }
            }
        }

        return null;
    }

    private function boolean(array $source, array $keys, bool $default): bool
    {
        foreach ($keys as $key) {
            if (! Arr::has($source, $key)) {
                continue;
            }

            return filter_var(
                Arr::get($source, $key),
                FILTER_VALIDATE_BOOL,
                FILTER_NULL_ON_FAILURE
            ) ?? $default;
        }

        return $default;
    }

    private function integer(array $source, array $keys, int $default): int
    {
        foreach ($keys as $key) {
            $value = Arr::get($source, $key);

            if (is_numeric($value)) {
                return max(0, (int) $value);
            }
        }

        return $default;
    }

    private function decimal(array $source, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = Arr::get($source, $key);

            if (is_numeric($value)) {
                return number_format((float) $value, 2, '.', '');
            }
        }

        return null;
    }

    private function date(array $source, array $keys): ?CarbonImmutable
    {
        foreach ($keys as $key) {
            $value = Arr::get($source, $key);

            if (is_string($value) && trim($value) !== '') {
                return CarbonImmutable::parse($value)->utc();
            }
        }

        return null;
    }
}
