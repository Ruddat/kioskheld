<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostcodeAvailabilityController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
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

        $apiUrl = config('services.justdeliver.kioskheld_api_url');
        $apiKey = config('services.justdeliver.kioskheld_api_key');

        if (blank($apiUrl) || blank($apiKey)) {
            Log::error('Kioskheld API config missing', [
                'api_url_present' => filled($apiUrl),
                'api_key_present' => filled($apiKey),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Die PLZ-Prüfung ist noch nicht korrekt konfiguriert.',
            ], 500);
        }

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
            ], fn($value) => filled($value));

            $response = Http::timeout(5)
                ->when(app()->environment('local'), fn($http) => $http->withoutVerifying())
                ->withHeaders([
                    'X-Kioskheld-Api-Key' => $apiKey,
                    'Accept' => 'application/json',
                ])
                ->get(rtrim($apiUrl, '/') . '/availability', $query);

            if (! $response->successful()) {
                Log::warning('Kioskheld API availability request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'postcode' => $postcode,
                    'city' => $city,
                    'district' => $district,
                ]);

                return response()->json([
                    'ok' => false,
                    'message' => 'Die Verfügbarkeit konnte gerade nicht geprüft werden. Bitte versuche es erneut.',
                ], 502);
            }

            $data = $response->json('data');

            Log::info('Kioskheld availability response', [
                'postcode' => $postcode,
                'mode' => $data['mode'] ?? null,
                'available' => $data['available'] ?? null,
                'shops_count' => count($data['shops'] ?? []),
            ]);


            if (! is_array($data)) {
                Log::warning('Kioskheld API availability returned invalid payload', [
                    'body' => $response->body(),
                    'postcode' => $postcode,
                    'city' => $city,
                    'district' => $district,
                ]);

                return response()->json([
                    'ok' => false,
                    'message' => 'Die Verfügbarkeit konnte gerade nicht korrekt geprüft werden.',
                ], 502);
            }

            $this->storeAvailabilityInSession(
                request: $request,
                postcode: $postcode,
                city: $city,
                district: $district,
                data: $data,
            );

Log::info('Kioskheld availability stored in session', [
                'postcode' => $postcode,
                'city' => $city,
                'district' => $district,
                'mode' => $data['mode'] ?? null,
                'available' => $data['available'] ?? null,
                'shops_count' => count($data['shops'] ?? []),
            ]);

            return response()->json([
                'ok' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Kioskheld API availability exception', [
                'message' => $exception->getMessage(),
                'postcode' => $postcode,
                'city' => $city,
                'district' => $district,
            ]);

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
        array $data
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

        $shops = $data['shops'] ?? [];
        $delivery = $data['delivery'] ?? [];
        $mode = $data['mode'] ?? 'none';

        $selectedShop = null;
        $selectedDelivery = null;

        if ($mode === 'single' && count($shops) === 1) {
            $selectedShop = $shops[0];

            $selectedDelivery = collect($delivery)
                ->firstWhere('shop_id', $selectedShop['id'] ?? null);
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
}
