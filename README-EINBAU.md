# Kioskheld SEO-Katalog – Einbau

## 1. Dateien kopieren

Alle Ordner aus diesem Paket in das Laravel-Projekt kopieren.
`routes/catalog-snippet.php` ist nur eine Einbauvorlage und wird nicht als eigene
Laravel-Routendatei geladen.

## 2. Bestehende Dateien ändern

Die Hinweise unter `patches/` ausführen:

- bootstrap/app.php
- config/services.php
- resources/views/layouts/marketing.blade.php
- routes/web.php
- routes/console.php
- .env.example

## 3. Ausführen

```bash
php artisan optimize:clear
php artisan migrate
php artisan route:list
php artisan schedule:list
php artisan kioskheld:catalog-sync
```

## 4. Manuelle URLs

```text
/de/produkte
/de/kategorien
/sitemap.xml
/sitemaps/products.xml
/sitemaps/categories.xml
```

## 5. Wichtiger API-Abgleich

Der Mapper akzeptiert derzeit die üblichen Feldnamen `id`, `name`, `slug`,
`category.id`, `lowest_price`, `currency`, `variants` und `updated_at`.

Beim ersten Sync kann die reale JustDeliver-Antwort abweichen. In diesem Fall
nur `CatalogPayloadMapper` und gegebenenfalls die Pagination-Erkennung im
`CatalogSynchronizer` anpassen. Die Datenbank-, Seiten- und Bestellarchitektur
muss dafür nicht geändert werden.

## 6. Rückfall

Vor dem produktiven Import:

```bash
php artisan migrate:status
```

Bei Problemen direkt nach dem Einbau:

```bash
php artisan migrate:rollback --step=3
php artisan optimize:clear
```

Die bestehende PLZ-, Warenkorb-, Checkout- und Bestelllogik wird durch diese
Dateien nicht verändert.
