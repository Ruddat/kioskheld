<?php

namespace App\Services\Catalog;

class CatalogSyncResult
{
    public int $categoriesReceived = 0;
    public int $categoriesCreated = 0;
    public int $categoriesUpdated = 0;
    public int $categoriesDeactivated = 0;
    public int $productsReceived = 0;
    public int $productsCreated = 0;
    public int $productsUpdated = 0;
    public int $productsDeactivated = 0;
    public int $pagesProcessed = 0;

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
