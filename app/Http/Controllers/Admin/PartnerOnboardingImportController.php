<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerOnboarding;
use App\Services\JustDeliverPartnerOnboardingClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class PartnerOnboardingImportController extends Controller
{
    public function __invoke(
        PartnerOnboarding $partnerOnboarding,
        JustDeliverPartnerOnboardingClient $client
    ): RedirectResponse {
        if (! $partnerOnboarding->isSubmitted()) {
            return back()->with(
                'error',
                'Das Onboarding wurde noch nicht vollständig abgesendet.'
            );
        }

        if ($partnerOnboarding->justdeliver_import_status === 'importing') {
            return back()->with(
                'error',
                'Dieses Onboarding wird bereits übertragen.'
            );
        }

        $partnerOnboarding->update([
            'justdeliver_import_status' => 'importing',
            'justdeliver_import_error' => null,
        ]);

        try {
            $result = $client->import($partnerOnboarding);

            $data = $result['data'];

            $partnerOnboarding->update([
                'justdeliver_import_status' => 'imported',
                'justdeliver_shop_id' => $data['shop_id'],
                'justdeliver_shop_slug' => $data['shop_slug'],
                'justdeliver_import_error' => null,
                'justdeliver_import_response' => $result['response'],
                'justdeliver_imported_at' => now(),
            ]);

            Log::info('Kioskheld partner onboarding imported to JustDeliver', [
                'partner_onboarding_id' => $partnerOnboarding->id,
                'partner_lead_id' => $partnerOnboarding->partner_lead_id,
                'justdeliver_shop_id' => $data['shop_id'],
                'justdeliver_shop_slug' => $data['shop_slug'],
                'created' => $data['created'] ?? null,
                'updated' => $data['updated'] ?? null,
            ]);

            $message = ($data['created'] ?? false)
                ? 'Der Shop-Entwurf wurde in JustDeliver erstellt.'
                : 'Der bestehende Shop-Entwurf wurde in JustDeliver aktualisiert.';

            return back()->with('status', $message);
        } catch (Throwable $exception) {
            $partnerOnboarding->update([
                'justdeliver_import_status' => 'failed',
                'justdeliver_import_error' => $exception->getMessage(),
            ]);

            Log::error('Kioskheld partner onboarding import failed', [
                'partner_onboarding_id' => $partnerOnboarding->id,
                'partner_lead_id' => $partnerOnboarding->partner_lead_id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with(
                'error',
                'Die Übertragung an JustDeliver ist fehlgeschlagen: '
                .$exception->getMessage()
            );
        }
    }
}
