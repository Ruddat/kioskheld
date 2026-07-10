<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerLead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PartnerLeadAdminController extends Controller
{
    public function index(): View
    {
        $partnerLeads = PartnerLead::query()
            ->latest()
            ->paginate(20);

        return view('admin.partner-leads.index', [
            'partnerLeads' => $partnerLeads,
        ]);
    }

public function show(PartnerLead $partnerLead): View
{
    $partnerLead->load('latestOnboarding');

    return view('admin.partner-leads.show', [
        'partnerLead' => $partnerLead,
    ]);
}

    public function updateStatus(Request $request, PartnerLead $partnerLead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'new',
                    'contacted',
                    'in_review',
                    'converted',
                    'rejected',
                ]),
            ],
        ]);

        $partnerLead->update([
            'status' => $validated['status'],
        ]);

        return back()->with('status', 'Status wurde aktualisiert.');
    }
}
