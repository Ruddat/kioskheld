<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerOnboarding;
use App\Services\JustDeliverPartnerOnboardingClient;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class PartnerOnboardingStatusController extends Controller
{
    public function __invoke(
        PartnerOnboarding $partnerOnboarding,
        JustDeliverPartnerOnboardingClient $client
    ): RedirectResponse {
        if (! $partnerOnboarding->justdeliver_shop_id) {
            return back()->with(
                'error',
                'Das Onboarding wurde noch nicht erfolgreich an JustDeliver übertragen.'
            );
        }

        try {
            $result = $client->status($partnerOnboarding);
            $data = $result['data'];

            $canImportProducts = (bool) data_get(
                $data,
                'capabilities.can_import_products',
                false
            );

            $canAcceptOrders = (bool) data_get(
                $data,
                'capabilities.can_accept_orders',
                false
            );

            $activatedAt = $partnerOnboarding->justdeliver_activated_at;

            if ($canAcceptOrders && $activatedAt === null) {
                $publishedAt = data_get($data, 'shop.portal_published_at');

                $activatedAt = $publishedAt
                    ? Carbon::parse($publishedAt)
                    : now();
            }

            $partnerOnboarding->update([
                'justdeliver_remote_status' => $data['import_status'] ?? null,
                'justdeliver_can_import_products' => $canImportProducts,
                'justdeliver_can_accept_orders' => $canAcceptOrders,
                'justdeliver_shop_id' => data_get(
                    $data,
                    'shop.id',
                    $partnerOnboarding->justdeliver_shop_id
                ),
                'justdeliver_shop_slug' => data_get(
                    $data,
                    'shop.slug',
                    $partnerOnboarding->justdeliver_shop_slug
                ),
                'justdeliver_status_response' => $result['response'],
                'justdeliver_status_error' => data_get($data, 'last_error'),
                'justdeliver_status_checked_at' => now(),
                'justdeliver_activated_at' => $activatedAt,
            ]);

            Log::info('Kioskheld JustDeliver onboarding status synchronized', [
                'partner_onboarding_id' => $partnerOnboarding->id,
                'external_reference' => $data['external_reference'] ?? null,
                'import_status' => $data['import_status'] ?? null,
                'can_import_products' => $canImportProducts,
                'can_accept_orders' => $canAcceptOrders,
            ]);

            $message = $canAcceptOrders
                ? 'Der Shop ist in JustDeliver aktiv und kann Bestellungen empfangen.'
                : 'Der JustDeliver-Status wurde aktualisiert. Der Shop ist noch nicht bestellbereit.';

            return back()->with('status', $message);
        } catch (Throwable $exception) {
            $partnerOnboarding->update([
                'justdeliver_status_error' => $exception->getMessage(),
                'justdeliver_status_checked_at' => now(),
            ]);

            Log::error('Kioskheld JustDeliver onboarding status sync failed', [
                'partner_onboarding_id' => $partnerOnboarding->id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with(
                'error',
                'Der JustDeliver-Status konnte nicht abgefragt werden: '
                .$exception->getMessage()
            );
        }
    }
}
