<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerOnboarding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerOnboardingController extends Controller
{
    public function show(string $token): View
    {
        $onboarding = PartnerOnboarding::query()
            ->where('token', $token)
            ->with('partnerLead')
            ->firstOrFail();

        abort_if($onboarding->isExpired(), 410, 'Dieser Onboarding-Link ist abgelaufen.');

        return view('pages.partner-onboarding', [
            'onboarding' => $onboarding,
            'lead' => $onboarding->partnerLead,
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $onboarding = PartnerOnboarding::query()
            ->where('token', $token)
            ->with('partnerLead')
            ->firstOrFail();

        abort_if($onboarding->isExpired(), 410, 'Dieser Onboarding-Link ist abgelaufen.');

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:160'],
            'contact_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:60'],
            'email' => ['nullable', 'email', 'max:160'],
            'street' => ['nullable', 'string', 'max:180'],
            'postcode' => ['required', 'regex:/^[0-9]{5}$/'],
            'city' => ['nullable', 'string', 'max:120'],

            'categories' => ['nullable', 'array'],
            'categories.*' => ['string', 'max:80'],
            'custom_categories' => ['nullable', 'string', 'max:1000'],
            'top_products' => ['nullable', 'string', 'max:2000'],

            'opening_hours' => ['required', 'array'],

            'delivery_enabled' => ['required', 'in:yes,no,maybe'],
            'delivery_postcodes' => ['nullable', 'string', 'max:1000'],
            'minimum_order_value' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'delivery_fee' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'free_delivery_from' => ['nullable', 'numeric', 'min:0', 'max:999'],

            'cash_enabled' => ['nullable', 'boolean'],
            'card_enabled' => ['nullable', 'boolean'],
            'card_minimum_order_value' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'card_fee_enabled' => ['nullable', 'boolean'],
            'card_fee_amount' => ['nullable', 'numeric', 'min:0', 'max:999'],

            'accept_terms' => ['accepted'],
            'confirm_data' => ['accepted'],
            'confirm_authorized' => ['accepted'],
        ], [
            'business_name.required' => 'Bitte gib den Kiosknamen ein.',
            'phone.required' => 'Bitte gib eine Telefonnummer ein.',
            'postcode.required' => 'Bitte gib eine Postleitzahl ein.',
            'postcode.regex' => 'Bitte gib eine gültige 5-stellige Postleitzahl ein.',
            'accept_terms.accepted' => 'Bitte bestätige die Partnerkonditionen.',
            'confirm_data.accepted' => 'Bitte bestätige die Richtigkeit deiner Angaben.',
            'confirm_authorized.accepted' => 'Bitte bestätige, dass du zur Übermittlung berechtigt bist.',
        ]);

        $onboarding->update([
            'status' => 'submitted',
            'business_data' => [
                'business_name' => $validated['business_name'],
                'contact_name' => $validated['contact_name'] ?? null,
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'street' => $validated['street'] ?? null,
                'postcode' => $validated['postcode'],
                'city' => $validated['city'] ?? null,
            ],
            'selected_categories' => [
                'categories' => $validated['categories'] ?? [],
                'custom_categories' => $validated['custom_categories'] ?? null,
                'top_products' => $validated['top_products'] ?? null,
            ],
            'opening_hours' => $validated['opening_hours'],
            'delivery_settings' => [
                'enabled' => $validated['delivery_enabled'],
                'postcodes' => $validated['delivery_postcodes'] ?? null,
                'minimum_order_value' => $validated['minimum_order_value'] ?? null,
                'delivery_fee' => $validated['delivery_fee'] ?? null,
                'free_delivery_from' => $validated['free_delivery_from'] ?? null,
            ],
            'payment_settings' => [
                'cash_enabled' => $request->boolean('cash_enabled'),
                'card_enabled' => $request->boolean('card_enabled'),
                'card_minimum_order_value' => $validated['card_minimum_order_value'] ?? null,
                'card_fee_enabled' => $request->boolean('card_fee_enabled'),
                'card_fee_amount' => $validated['card_fee_amount'] ?? null,
            ],
            'accepted_terms_at' => now(),
            'accepted_terms_ip' => $request->ip(),
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('partner.onboarding.thank-you')
            ->with('status', 'Danke. Deine Startdaten wurden erfolgreich übermittelt.');
    }

    public function thankYou(): View
    {
        return view('pages.partner-onboarding-thank-you');
    }

    private function categories(): array
    {
        return [
            'Getränke',
            'Energy Drinks',
            'Bier & Mixgetränke',
            'Chips & Snacks',
            'Süßwaren',
            'Schokolade',
            'Eis',
            'Tabakwaren',
            'Vapes / Zubehör',
            'Haushaltsartikel',
            'Drogerie',
            'Partybedarf',
            'Bundles / Sparpakete',
            'Sonstiges',
        ];
    }
}
