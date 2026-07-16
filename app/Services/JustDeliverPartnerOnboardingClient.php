<?php

namespace App\Services;

use App\Models\PartnerOnboarding;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class JustDeliverPartnerOnboardingClient
{
    public function import(PartnerOnboarding $onboarding): array
    {
        $this->ensureConfigurationExists();

        try {
            $response = $this->httpClient()
                ->post(
                    $this->endpoint(),
                    $this->buildPayload($onboarding)
                );
        } catch (ConnectionException $exception) {
            throw new RuntimeException(
                'JustDeliver konnte nicht erreicht werden: '.$exception->getMessage(),
                previous: $exception
            );
        }

        if (! $response->successful()) {
            throw new RuntimeException(
                $this->errorMessageFromResponse($response)
            );
        }

        $data = $response->json('data');

        if (! is_array($data)) {
            throw new RuntimeException(
                'JustDeliver hat keine gültigen Importdaten zurückgegeben.'
            );
        }

        if (empty($data['shop_id']) || empty($data['shop_slug'])) {
            throw new RuntimeException(
                'JustDeliver hat keine Shop-ID oder keinen Shop-Slug zurückgegeben.'
            );
        }

        return [
            'status_code' => $response->status(),
            'data' => $data,
            'response' => $response->json(),
        ];
    }

    public function buildPayload(PartnerOnboarding $onboarding): array
    {
        $onboarding->loadMissing('partnerLead');

        $business = $onboarding->business_data ?? [];
        $catalog = $onboarding->selected_categories ?? [];
        $delivery = $onboarding->delivery_settings ?? [];
        $payment = $onboarding->payment_settings ?? [];

        $deliveryStatus = (string) ($delivery['enabled'] ?? 'maybe');

        return [
            'external_reference' => $this->externalReference($onboarding),
            'source' => 'kioskheld',
            'status' => 'submitted',

            'business' => [
                'name' => $business['business_name'] ?? null,
                'contact_name' => $business['contact_name'] ?? null,
                'phone' => $business['phone'] ?? null,
                'email' => $business['email'] ?? null,
                'street' => $business['street'] ?? null,
                'postcode' => $business['postcode'] ?? null,
                'city' => $business['city'] ?? null,
            ],

            'catalog' => [
                'categories' => array_values(
                    array_filter(
                        Arr::wrap($catalog['categories'] ?? []),
                        fn ($category) => is_string($category) && filled($category)
                    )
                ),
                'custom_categories' => $catalog['custom_categories'] ?? null,
                'top_products' => $catalog['top_products'] ?? null,
            ],

            'opening_hours' => $this->normalizeOpeningHours(
                $onboarding->opening_hours ?? []
            ),

            'delivery' => [
                'enabled' => $deliveryStatus === 'yes',
                'status' => $deliveryStatus,
                'postcodes' => $this->parsePostcodes(
                    $delivery['postcodes'] ?? null
                ),
                'minimum_order_value' => $this->decimalOrNull(
                    $delivery['minimum_order_value'] ?? null
                ),
                'delivery_fee' => $this->decimalOrNull(
                    $delivery['delivery_fee'] ?? null
                ),
                'free_delivery_from' => $this->decimalOrNull(
                    $delivery['free_delivery_from'] ?? null
                ),
            ],

            'payment' => [
                'cash_enabled' => (bool) ($payment['cash_enabled'] ?? false),
                'card_at_door_enabled' => (bool) ($payment['card_enabled'] ?? false),
                'card_minimum_order_value' => $this->decimalOrNull(
                    $payment['card_minimum_order_value'] ?? null
                ),
                'card_fee_enabled' => (bool) ($payment['card_fee_enabled'] ?? false),
                'card_fee_amount' => $this->decimalOrNull(
                    $payment['card_fee_amount'] ?? null
                ),
            ],

            'commercial' => [
                'commission_percent' => 3.00,
                'terms_accepted' => $onboarding->accepted_terms_at !== null,
                'data_confirmed' => $onboarding->accepted_terms_at !== null,
                'authorized_confirmed' => $onboarding->accepted_terms_at !== null,
                'accepted_at' => $onboarding->accepted_terms_at?->toIso8601String(),
            ],

            'meta' => [
                'partner_lead_id' => $onboarding->partner_lead_id,
                'partner_onboarding_id' => $onboarding->id,
                'submitted_at' => $onboarding->submitted_at?->toIso8601String(),
            ],
        ];
    }

    private function normalizeOpeningHours(array $openingHours): array
    {
        $days = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ];

        $normalized = [];

        foreach ($days as $day) {
            $settings = $openingHours[$day] ?? [];

            $isOpen = filter_var(
                $settings['open'] ?? false,
                FILTER_VALIDATE_BOOL
            );

            $normalized[$day] = [
                'open' => $isOpen,
                'from' => $isOpen
                    ? $this->normalizeTime($settings['from'] ?? null)
                    : null,
                'to' => $isOpen
                    ? $this->normalizeTime($settings['to'] ?? null)
                    : null,
            ];
        }

        return $normalized;
    }

    private function normalizeTime(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        if (! preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $value)) {
            return null;
        }

        return $value;
    }

    private function parsePostcodes(mixed $value): array
    {
        if (is_array($value)) {
            $parts = $value;
        } elseif (is_string($value)) {
            $parts = preg_split('/[\s,;]+/', $value) ?: [];
        } else {
            return [];
        }

        return collect($parts)
            ->map(fn ($postcode) => trim((string) $postcode))
            ->filter(fn ($postcode) => preg_match('/^\d{5}$/', $postcode) === 1)
            ->unique()
            ->values()
            ->all();
    }

    private function decimalOrNull(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            return null;
        }

        return round((float) $value, 2);
    }

    private function httpClient(): PendingRequest
    {
        $request = Http::acceptJson()
            ->asJson()
            ->timeout(15)
            ->connectTimeout(5)
            ->withHeaders([
                'X-Kioskheld-Api-Key' => config(
                    'services.justdeliver.kioskheld_api_key'
                ),
            ]);

        if (! config('services.justdeliver.kioskheld_verify_ssl', true)) {
            $request = $request->withoutVerifying();
        }

        return $request;
    }

    private function endpoint(): string
    {
        return rtrim(
            (string) config('services.justdeliver.kioskheld_api_url'),
            '/'
        ).'/partner-onboardings';
    }

    private function ensureConfigurationExists(): void
    {
        if (blank(config('services.justdeliver.kioskheld_api_url'))) {
            throw new RuntimeException(
                'JUSTDELIVER_KIOSKHELD_API_URL ist nicht konfiguriert.'
            );
        }

        if (blank(config('services.justdeliver.kioskheld_api_key'))) {
            throw new RuntimeException(
                'JUSTDELIVER_KIOSKHELD_API_KEY ist nicht konfiguriert.'
            );
        }
    }

    private function errorMessageFromResponse(Response $response): string
    {
        $message = $response->json('message');

        if (! is_string($message) || blank($message)) {
            $message = 'JustDeliver hat den Import abgelehnt.';
        }

        $errors = $response->json('errors');

        if (is_array($errors) && count($errors) > 0) {
            $errorMessages = collect($errors)
                ->flatten()
                ->filter(fn ($error) => is_string($error))
                ->implode(' ');

            if (filled($errorMessages)) {
                $message .= ' '.$errorMessages;
            }
        }

        return sprintf(
            '%s [HTTP %d]',
            trim($message),
            $response->status()
        );
    }

public function status(PartnerOnboarding $onboarding): array
{
    $this->ensureConfigurationExists();

    $externalReference = $this->externalReference($onboarding);

    try {
        $response = $this->httpClient()
            ->get(
                $this->statusEndpoint($externalReference)
            );
    } catch (ConnectionException $exception) {
        throw new RuntimeException(
            'JustDeliver konnte nicht erreicht werden: '.$exception->getMessage(),
            previous: $exception
        );
    }

    if (! $response->successful()) {
        Log::warning('JustDeliver onboarding status request failed', [
            'partner_onboarding_id' => $onboarding->id,
            'external_reference' => $externalReference,
            'status' => $response->status(),
            'response_body' => $response->body(),
        ]);

        throw new RuntimeException(
            $this->errorMessageFromResponse($response)
        );
    }

    $data = $response->json('data');

    if (! is_array($data)) {
        throw new RuntimeException(
            'JustDeliver hat keine gültigen Statusdaten zurückgegeben.'
        );
    }

    if (($data['external_reference'] ?? null) !== $externalReference) {
        throw new RuntimeException(
            'Die JustDeliver-Antwort gehört nicht zum angefragten Onboarding.'
        );
    }

    return [
        'status_code' => $response->status(),
        'data' => $data,
        'response' => $response->json(),
    ];
}


private function externalReference(PartnerOnboarding $onboarding): string
{
    return 'kioskheld-onboarding-'.$onboarding->id;
}

private function statusEndpoint(string $externalReference): string
{
    return rtrim(
        (string) config('services.justdeliver.kioskheld_api_url'),
        '/'
    ).'/partner-onboardings/'.rawurlencode($externalReference).'/status';
}


}
