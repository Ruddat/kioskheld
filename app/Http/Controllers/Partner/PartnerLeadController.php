<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StorePartnerLeadRequest;
use App\Models\PartnerLead;

class PartnerLeadController extends Controller
{
    public function store(StorePartnerLeadRequest $request): RedirectResponse
    {
        PartnerLead::create([
            ...$request->validated(),
            'status' => 'new',
            'source' => 'kioskheld',
            'metadata' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return redirect()
            ->route('partner.thank-you')
            ->with('status', 'Danke. Wir bereiten deinen Kioskheld-Start vor und melden uns kurzfristig.');
    }
}
