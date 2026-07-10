<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerLead;
use App\Models\PartnerOnboarding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class PartnerOnboardingAdminController extends Controller
{
    public function store(PartnerLead $partnerLead): RedirectResponse
    {
        $onboarding = PartnerOnboarding::create([
            'partner_lead_id' => $partnerLead->id,
            'token' => Str::random(64),
            'status' => 'sent',
            'business_data' => [
                'business_name' => $partnerLead->business_name,
                'contact_name' => $partnerLead->contact_name,
                'phone' => $partnerLead->phone,
                'email' => $partnerLead->email,
                'street' => $partnerLead->street,
                'postcode' => $partnerLead->postcode,
                'city' => $partnerLead->city,
            ],
            'expires_at' => now()->addDays(14),
        ]);

        return back()->with(
            'status',
            'Onboarding-Link wurde erstellt: '.route('partner.onboarding.show', $onboarding->token)
        );
    }
}
