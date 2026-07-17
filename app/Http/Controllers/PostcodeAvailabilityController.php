<?php

namespace App\Http\Controllers;

use App\Services\Analytics\AnalyticsEventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostcodeAvailabilityController extends Controller
{
    public function __invoke(
        Request $request,
        AnalyticsEventService $analytics,
    ): JsonResponse {
        $validator = Validator::make($request->all(), [
            'postcode' => ['required', 'regex:/^[0-9]{5}$/'],
            'city' => ['nullable', 'string', 'max:120'],
            'district' => ['nullable', 'string', 'max:120'],
        ], [
            'postcode.required' => 'Bitte gib deine Postleitzahl ein.',
            'postcode.regex' => 'Bitte gib eine gültige 5-stellige Postleitzahl ein.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $postcode = $request->string('postcode')->toString();
        $city = $request->string('city')->toString() ?: null;
        $district = $request->string('district')->toString() ?: null;

        /*
         * Jede gültige PLZ-Prüfung wird als gestartete Suche erfasst.
         */
        $analytics->track('postcode_searched', [
            'postcode' => $postcode,
            'metadata' => array_filter([
                'city' => $city,
                'district' => $district,
            ], fn ($value) => filled($value)),
        ], $request);

        $apiUrl = config('services.justdeliver.kioskheld_api_url');
        $apiKey = config('services.justdeliver.kioskheld_api_key');

        if (blank($apiUrl) || blank($apiKey)) {
            Log::error('Kioskheld API config missing', [
                'api_url_present' => filled($apiUrl),
                'api_key_present' => filled($apiKey),
            ]);

            $analytics->track('postcode_check_failed', [
                'postcode' => $postcode,
                'metadata' => [
                    'reason' => 'configuration_missing',
                ],
            ], $request);

            return response()->json([
                'ok' => false,
                'message' => 'Die PLZ-Prüfung ist noch nicht korrekt konfiguriert.',
            ], 500);
        }

        $startedAt = microtime(true);

        try {
            $query = array_filter([
                'postcode' => $postcode,
                'city' => $city,
                'district' => $district,
                'external_session_id' => $request->session()->getId(),
                'referrer' => $request->headers->get('referer'),
                'utm_source' => $request->string('utm_source')->toString() ?: null,
                'utm_campaign' => $request->string('utm_campaign')->toString() ?: null,
                'landing_page' => $request->headers->get('referer'),
            ], fn ($value) => filled($value));

            $response = Http::timeout(5)
                ->when(
                    app()->environment('local'),
                    fn ($http) => $http->withoutVerifying()
                )
                ->withHeaders([
                    'X-Kioskheld-Api-Key' => $apiKey,
                    'Accept' => 'application/json',
                ])
                ->get(
                    rtrim($apiUrl, '/').'/availability',
                    $query
                );

            $responseTimeMs = (int) round(
                (microtime(true) - $startedAt) * 1000
            );

            if (! $response->successful()) {
                Log::warning('Kioskheld API availability request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'postcode' => $postcode,
                    'city' => $city,
                    'district' => $district,
                ]);

                $analytics->track('postcode_check_failed', [
                    'postcode' => $postcode,
                    'metadata' => [
                        'reason' => 'api_response_error',
                        'status' => $response->status(),
                        'response_time_ms' => $responseTimeMs,
                    ],
                ], $request);

                return response()->json([
                    'ok' => false,
                    'message' => 'Die Verfügbarkeit konnte gerade nicht geprüft werden. Bitte versuche es erneut.',
                ], 502);
            }

            $data = $response->json('data');

            if (! is_array($data)) {
                Log::warning('Kioskheld API availability returned invalid payload', [
                    'body' => $response->body(),
                    'postcode' => $postcode,
                    'city' => $city,
                    'district' => $district,
                ]);

                $analytics->track('postcode_check_failed', [
                    'postcode' => $postcode,
                    'metadata' => [
                        'reason' => 'invalid_api_payload',
                        'response_time_ms' => $responseTimeMs,
                    ],
                ], $request);

                return response()->json([
                    'ok' => false,
                    'message' => 'Die Verfügbarkeit konnte gerade nicht korrekt geprüft werden.',
                ], 502);
            }

            $shops = is_array($data['shops'] ?? null)
                ? $data['shops']
                : [];

            $shopCount = count($shops);
            $available = ($data['available'] ?? false) === true;
            $requiresDistrict = ($data['requires_district'] ?? false) === true;
            $mode = (string) ($data['mode'] ?? 'none');

            Log::info('Kioskheld availability response', [
                'postcode' => $postcode,
                'mode' => $mode,
                'available' => $available,
                'requires_district' => $requiresDistrict,
                'shops_count' => $shopCount,
            ]);

            $this->storeAvailabilityInSession(
                request: $request,
                postcode: $postcode,
                city: $city,
                district: $district,
                data: $data,
            );

            /*
             * Bezirksauswahl ist weder erfolgreich noch nicht verfügbar.
             * Die Suche benötigt zunächst weitere Angaben.
             */
            if ($requiresDistrict) {
                $analytics->track('postcode_requires_district', [
                    'postcode' => $postcode,
                    'metadata' => [
                        'city' => $city,
                        'district' => $district,
                        'mode' => $mode,
                        'suggestions_count' => count(
                            is_array($data['suggestions'] ?? null)
                                ? $data['suggestions']
                                : []
                        ),
                        'response_time_ms' => $responseTimeMs,
                    ],
                ], $request);
            } elseif ($available) {
                $analytics->track('postcode_available', [
                    'postcode' => $postcode,
                    'shop_id' => $this->resolveSingleShopId($shops),
                    'metadata' => [
                        'city' => $city,
                        'district' => $district,
                        'mode' => $mode,
                        'shop_count' => $shopCount,
                        'response_time_ms' => $responseTimeMs,
                    ],
                ], $request);
            } else {
                $analytics->track('postcode_unavailable', [
                    'postcode' => $postcode,
                    'metadata' => [
                        'city' => $city,
                        'district' => $district,
                        'mode' => $mode,
                        'shop_count' => $shopCount,
                        'response_time_ms' => $responseTimeMs,
                    ],
                ], $request);
            }

            Log::info('Kioskheld availability stored in session', [
                'postcode' => $postcode,
                'city' => $city,
                'district' => $district,
                'mode' => $mode,
                'available' => $available,
                'requires_district' => $requiresDistrict,
                'shops_count' => $shopCount,
            ]);

            return response()->json([
                'ok' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $exception) {
            $responseTimeMs = (int) round(
                (microtime(true) - $startedAt) * 1000
            );

            Log::error('Kioskheld API availability exception', [
                'message' => $exception->getMessage(),
                'postcode' => $postcode,
                'city' => $city,
                'district' => $district,
            ]);

            $analytics->track('postcode_check_failed', [
                'postcode' => $postcode,
                'metadata' => [
                    'reason' => 'exception',
                    'exception' => class_basename($exception),
                    'response_time_ms' => $responseTimeMs,
                ],
            ], $request);

            return response()->json([
                'ok' => false,
                'message' => 'Die PLZ-Prüfung ist gerade nicht erreichbar.',
            ], 500);
        }
    }

    private function storeAvailabilityInSession(
        Request $request,
        string $postcode,
        ?string $city,
        ?string $district,
        array $data,
    ): void {
        if (($data['requires_district'] ?? false) === true) {
            $request->session()->put('kioskheld', [
                'available' => false,
                'postcode' => $postcode,
                'city' => $city,
                'district' => $district,
                'mode' => $data['mode'] ?? 'requires_district',
                'requires_district' => true,
                'suggestions' => $data['suggestions'] ?? [],
                'shops' => [],
                'delivery' => [],
                'selected_shop_id' => null,
                'selected_shop_slug' => null,
                'delivery_rule' => null,
                'checked_at' => now()->toIso8601String(),
            ]);

            return;
        }

        if (($data['available'] ?? false) !== true) {
            $request->session()->put('kioskheld', [
                'available' => false,
                'postcode' => $postcode,
                'city' => $city,
                'district' => $district,
                'mode' => $data['mode'] ?? 'none',
                'requires_district' => false,
                'suggestions' => [],
                'shops' => [],
                'delivery' => [],
                'selected_shop_id' => null,
                'selected_shop_slug' => null,
                'delivery_rule' => null,
                'checked_at' => now()->toIso8601String(),
            ]);

            return;
        }

        $shops = is_array($data['shops'] ?? null)
            ? $data['shops']
            : [];

        $delivery = is_array($data['delivery'] ?? null)
            ? $data['delivery']
            : [];

        $mode = (string) ($data['mode'] ?? 'none');

        $selectedShop = null;
        $selectedDelivery = null;

        if ($mode === 'single' && count($shops) === 1) {
            $selectedShop = $shops[0];

            $selectedDelivery = collect($delivery)
                ->firstWhere(
                    'shop_id',
                    $selectedShop['id'] ?? null
                );
        }

        $request->session()->put('kioskheld', [
            'available' => true,
            'postcode' => $postcode,
            'city' => $city,
            'district' => $district,
            'mode' => $mode,
            'requires_district' => false,
            'suggestions' => [],
            'shops' => $shops,
            'delivery' => $delivery,
            'selected_shop_id' => $selectedShop['id'] ?? null,
            'selected_shop_slug' => $selectedShop['slug'] ?? null,
            'delivery_rule' => $selectedDelivery['rule'] ?? null,
            'checked_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * Gibt nur dann eine Shop-ID zurück, wenn genau ein Shop gefunden wurde.
     *
     * @param array<int, mixed> $shops
     */
    private function resolveSingleShopId(array $shops): ?int
    {
        if (count($shops) !== 1) {
            return null;
        }

        $shopId = $shops[0]['id'] ?? null;

        return is_numeric($shopId)
            ? (int) $shopId
            : null;
    }
}
