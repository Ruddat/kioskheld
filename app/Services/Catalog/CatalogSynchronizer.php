<?php

namespace App\Services\Catalog;

use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Services\JustDeliver\JustDeliverCatalogClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class CatalogSynchronizer
{
    public function __construct(
        private readonly JustDeliverCatalogClient $client,
        private readonly CatalogPayloadMapper $mapper,
    ) {
    }

    public function fullSync(): CatalogSyncResult
    {
        $lock = Cache::lock('kioskheld:catalog-sync', 3600);

        if (! $lock->get()) {
            throw new RuntimeException('Eine Katalogsynchronisierung läuft bereits.');
        }

        $startedAt = now('UTC');
        $result = new CatalogSyncResult();

        Log::info('Kioskheld catalog sync started', [
            'sync_started_at' => $startedAt->toIso8601String(),
        ]);

        try {
            $this->syncCategories($startedAt, $result);
            $this->syncProducts($startedAt, $result);

            $result->productsDeactivated = CatalogProduct::query()
                ->where('is_active', true)
                ->where(function ($query) use ($startedAt): void {
                    $query->whereNull('last_synced_at')
                        ->orWhere('last_synced_at', '<', $startedAt);
                })
                ->update(['is_active' => false]);

            $result->categoriesDeactivated = CatalogCategory::query()
                ->where('is_active', true)
                ->where(function ($query) use ($startedAt): void {
                    $query->whereNull('last_synced_at')
                        ->orWhere('last_synced_at', '<', $startedAt);
                })
                ->update(['is_active' => false]);

            Cache::forget('catalog:sitemap:products');
            Cache::forget('catalog:sitemap:categories');

            Log::info('Kioskheld catalog sync finished', [
                ...$result->toArray(),
                'sync_started_at' => $startedAt->toIso8601String(),
                'sync_finished_at' => now('UTC')->toIso8601String(),
                'duration_seconds' => $startedAt->diffInSeconds(now('UTC')),
                'status' => 'success',
            ]);

            return $result;
        } catch (Throwable $exception) {
            Log::error('Kioskheld catalog sync failed', [
                ...$result->toArray(),
                'sync_started_at' => $startedAt->toIso8601String(),
                'sync_finished_at' => now('UTC')->toIso8601String(),
                'status' => 'failed',
                'error_class' => $exception::class,
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        } finally {
            $lock->release();
        }
    }

    private function syncCategories($syncedAt, CatalogSyncResult $result): void
    {
        $page = 1;

        do {
            $payload = $this->client->categories([
                'page' => $page,
                'per_page' => config(
                    'services.justdeliver.catalog_sync_per_page',
                    100
                ),
            ]);

            $items = $this->items($payload);

            foreach ($items as $source) {
                $mapped = $this->mapper->category($source);
                $result->categoriesReceived++;

                DB::transaction(function () use ($mapped, $syncedAt, $result): void {
                    $category = CatalogCategory::query()
                        ->where('external_id', $mapped['external_id'])
                        ->first();

                    $attributes = Arr::except($mapped, ['slug_candidate']);
                    $attributes['last_synced_at'] = $syncedAt;

                    if ($category === null) {
                        $attributes['slug'] = $this->uniqueCategorySlug(
                            $mapped['slug_candidate'],
                            $mapped['external_id']
                        );

                        CatalogCategory::query()->create($attributes);
                        $result->categoriesCreated++;

                        return;
                    }

                    $category->fill($attributes)->save();
                    $result->categoriesUpdated++;
                });
            }

            $result->pagesProcessed++;
            $page++;
        } while ($this->hasNextPage($payload, $page - 1, count($items)));
    }

    private function syncProducts($syncedAt, CatalogSyncResult $result): void
    {
        $page = 1;

        do {
            $payload = $this->client->products([
                'page' => $page,
                'per_page' => config(
                    'services.justdeliver.catalog_sync_per_page',
                    100
                ),
                'include_unavailable' => 0,
            ]);

            $items = $this->items($payload);

            foreach ($items as $source) {
                $mapped = $this->mapper->product($source);
                $result->productsReceived++;

                DB::transaction(function () use ($mapped, $syncedAt, $result): void {
                    $categoryId = null;

                    if ($mapped['category_external_id'] !== null) {
                        $categoryId = CatalogCategory::query()
                            ->where(
                                'external_id',
                                $mapped['category_external_id']
                            )
                            ->value('id');
                    }

                    $product = CatalogProduct::query()
                        ->where('external_id', $mapped['external_id'])
                        ->first();

                    $attributes = Arr::except($mapped, [
                        'category_external_id',
                        'slug_candidate',
                        'variants',
                    ]);

                    $attributes['catalog_category_id'] = $categoryId;
                    $attributes['last_synced_at'] = $syncedAt;

                    if ($product === null) {
                        $attributes['slug'] = $this->uniqueProductSlug(
                            $mapped['slug_candidate'],
                            $mapped['external_id']
                        );

                        $product = CatalogProduct::query()->create($attributes);
                        $result->productsCreated++;
                    } else {
                        $product->fill($attributes)->save();
                        $result->productsUpdated++;
                    }

                    $seenVariantIds = [];

                    foreach ($mapped['variants'] as $variant) {
                        $seenVariantIds[] = $variant['external_id'];

                        $product->variants()->updateOrCreate(
                            ['external_id' => $variant['external_id']],
                            [
                                ...Arr::except($variant, ['external_id']),
                                'last_synced_at' => $syncedAt,
                            ]
                        );
                    }

                    if ($seenVariantIds !== []) {
                        $product->variants()
                            ->whereNotIn('external_id', $seenVariantIds)
                            ->update(['is_active' => false]);
                    }
                });
            }

            $result->pagesProcessed++;
            $page++;
        } while ($this->hasNextPage($payload, $page - 1, count($items)));
    }

    private function items(array $payload): array
    {
        $items = $payload['data'] ?? $payload['items'] ?? null;

        if (! is_array($items)) {
            throw new RuntimeException(
                'Die JustDeliver-Katalogantwort enthält keine Datenliste.'
            );
        }

        return array_values(array_filter($items, 'is_array'));
    }

    private function hasNextPage(array $payload, int $currentPage, int $count): bool
    {
        $meta = is_array($payload['meta'] ?? null) ? $payload['meta'] : [];

        $lastPage = $meta['last_page']
            ?? $payload['last_page']
            ?? null;

        if (is_numeric($lastPage)) {
            return $currentPage < (int) $lastPage;
        }

        $next = $payload['links']['next']
            ?? $payload['next_page_url']
            ?? null;

        if (is_string($next) && $next !== '') {
            return true;
        }

        return $count >= (int) config(
            'services.justdeliver.catalog_sync_per_page',
            100
        );
    }

    private function uniqueCategorySlug(string $candidate, string $externalId): string
    {
        $candidate = Str::slug($candidate) ?: 'kategorie-'.$externalId;

        if (! CatalogCategory::query()->where('slug', $candidate)->exists()) {
            return $candidate;
        }

        return $candidate.'-'.Str::slug($externalId);
    }

    private function uniqueProductSlug(string $candidate, string $externalId): string
    {
        $candidate = Str::slug($candidate) ?: 'produkt-'.$externalId;

        if (! CatalogProduct::query()->where('slug', $candidate)->exists()) {
            return $candidate;
        }

        return $candidate.'-'.Str::slug($externalId);
    }
}
