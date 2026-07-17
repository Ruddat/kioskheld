<?php

namespace App\Services\Analytics;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsEventService
{
    /**
     * Ein Analytics-Ereignis speichern.
     *
     * @param array<string, mixed> $data
     */
    public function track(
        string $eventName,
        array $data = [],
        ?Request $request = null,
    ): void {
        $request ??= request();

        try {
            DB::table('analytics_events')->insert([
                'event_name' => mb_substr($eventName, 0, 100),
                'visitor_hash' => $this->visitorHash($request),
                'session_id' => $request->hasSession()
                    ? mb_substr($request->session()->getId(), 0, 120)
                    : null,
                'locale' => mb_substr(
                    (string) ($data['locale'] ?? app()->getLocale()),
                    0,
                    10
                ),
                'postcode' => $this->nullableString(
                    $data['postcode'] ?? null,
                    10
                ),
                'shop_id' => $this->nullableInteger(
                    $data['shop_id'] ?? null
                ),
                'product_id' => $this->nullableInteger(
                    $data['product_id'] ?? null
                ),
                'category_id' => $this->nullableInteger(
                    $data['category_id'] ?? null
                ),
                'external_order_id' => $this->nullableString(
                    $data['external_order_id'] ?? null,
                    120
                ),
                'route_name' => $this->nullableString(
                    $request->route()?->getName(),
                    200
                ),
                'url' => mb_substr($request->fullUrl(), 0, 2000),
                'referer' => $this->nullableString(
                    $request->header('referer'),
                    2000
                ),
                'metadata' => $this->encodeMetadata(
                    $data['metadata'] ?? []
                ),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            /*
             * Analytics darf niemals den eigentlichen Request beschädigen.
             */
        }
    }

    private function visitorHash(Request $request): ?string
    {
        $ipAddress = $request->ip();

        if (! is_string($ipAddress) || $ipAddress === '') {
            return null;
        }

        return hash_hmac(
            'sha256',
            $ipAddress,
            (string) config('app.key'),
        );
    }

    private function nullableString(
        mixed $value,
        int $maximumLength,
    ): ?string {
        if (! is_string($value) && ! is_numeric($value)) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return mb_substr($value, 0, $maximumLength);
    }

    private function nullableInteger(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }

    /**
     * @param mixed $metadata
     */
    private function encodeMetadata(mixed $metadata): ?string
    {
        if (! is_array($metadata) || $metadata === []) {
            return null;
        }

        $encoded = json_encode(
            $metadata,
            JSON_THROW_ON_ERROR
            | JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES,
        );

        return $encoded !== false ? $encoded : null;
    }
}
