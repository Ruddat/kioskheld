<?php

namespace App\Console\Commands;

use App\Services\Catalog\CatalogSynchronizer;
use Illuminate\Console\Command;
use Throwable;

class SyncKioskheldCatalog extends Command
{
    protected $signature = 'kioskheld:catalog-sync';

    protected $description = 'Synchronisiert den öffentlichen Kioskheld-Katalog aus JustDeliver.';

    public function handle(CatalogSynchronizer $synchronizer): int
    {
        if (! config('services.justdeliver.catalog_sync_enabled', true)) {
            $this->warn('Die Katalogsynchronisierung ist deaktiviert.');

            return self::SUCCESS;
        }

        try {
            $result = $synchronizer->fullSync();
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->table(
            ['Bereich', 'Empfangen', 'Neu', 'Aktualisiert', 'Deaktiviert'],
            [
                [
                    'Kategorien',
                    $result->categoriesReceived,
                    $result->categoriesCreated,
                    $result->categoriesUpdated,
                    $result->categoriesDeactivated,
                ],
                [
                    'Produkte',
                    $result->productsReceived,
                    $result->productsCreated,
                    $result->productsUpdated,
                    $result->productsDeactivated,
                ],
            ]
        );

        $this->info('Kioskheld-Katalog wurde erfolgreich synchronisiert.');

        return self::SUCCESS;
    }
}
