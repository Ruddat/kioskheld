<?php

namespace App\Services\JustDeliver;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JsonException;
use Throwable;

class JustDeliverCatalogClient
{
    public function products(array $query = []): array
    {
        return $this->get('/catalog/products', $query);
    }

    public function product(int|string $product): array
    {
        return $this->get('/catalog/products/'.rawurlencode((string) $product));
    }

    public function categories(array $query = []): array
    {
        return $this->get('/catalog/categories', $query);
    }

    public function category(int|string $category): array
    {
        return $this->get('/catalog/categories/'.rawurlencode((string) $category));
    }

    private function get(string $path, array $query = []): array
    {
        $this->ensureConfigured();

        try {
            $response = $this->request()->get($this->url($path), $query);
        } catch (ConnectionException $exception) {
            throw new JustDeliverCatalogException(
                'JustDeliver konnte nicht erreicht werden.',
                previous: $exception
            );
        }

        $this->ensureSuccessful($response);

        try {
            $payload = json_decode(
                $response->body(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $exception) {
            throw new JustDeliverCatalogException(
                'JustDeliver hat ungültiges JSON zurückgegeben.',
                previous: $exception
            );
        }

        if (! is_array($payload)) {
            throw new JustDeliverCatalogException(
                'JustDeliver hat keine gültige Katalogantwort zurückgegeben.'
            );
        }

        return $payload;
    }

    private function request(): PendingRequest
    {
        $request = Http::acceptJson()
            ->timeout((int) config('services.justdeliver.catalog_timeout', 20))
            ->connectTimeout((int) config('services.justdeliver.catalog_connect_timeout', 5))
            ->withHeaders([
                'X-Kioskheld-Api-Key' => (string) config(
                    'services.justdeliver.kioskheld_api_key'
                ),
            ])
            ->retry(
                2,
                300,
                fn (Throwable $exception, PendingRequest $request) =>
                    $exception instanceof ConnectionException,
                throw: false
            );

        if (! config('services.justdeliver.kioskheld_verify_ssl', true)) {
            $request = $request->withoutVerifying();
        }

        return $request;
    }

    private function ensureSuccessful(Response $response): void
    {
        if ($response->successful()) {
            return;
        }

        $message = match ($response->status()) {
            401 => 'JustDeliver hat den Kioskheld-API-Key abgelehnt.',
            403 => 'JustDeliver hat den Zugriff auf den Katalog verweigert.',
            404 => 'Der angeforderte JustDeliver-Katalogendpunkt wurde nicht gefunden.',
            422 => 'JustDeliver hat die Kataloganfrage als ungültig abgelehnt.',
            default => 'JustDeliver-Kataloganfrage fehlgeschlagen.',
        };

        throw new JustDeliverCatalogException(
            $message.' [HTTP '.$response->status().']'
        );
    }

    private function url(string $path): string
    {
        return rtrim(
            (string) config('services.justdeliver.kioskheld_api_url'),
            '/'
        ).$path;
    }

    private function ensureConfigured(): void
    {
        if (blank(config('services.justdeliver.kioskheld_api_url'))) {
            throw new JustDeliverCatalogException(
                'JUSTDELIVER_KIOSKHELD_API_URL ist nicht konfiguriert.'
            );
        }

        if (blank(config('services.justdeliver.kioskheld_api_key'))) {
            throw new JustDeliverCatalogException(
                'JUSTDELIVER_KIOSKHELD_API_KEY ist nicht konfiguriert.'
            );
        }
    }
}
