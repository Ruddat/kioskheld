<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bestellung erhalten · Kioskheld</title>

    @vite(['resources/css/marketing.css', 'resources/js/marketing-home.js'])
</head>

<body>
    <x-marketing.nav />

    @php
        $response = $order['response'] ?? [];
        $data = $order['data'] ?? [];
        $payload = $order['payload'] ?? [];
        $validated = $order['validated'] ?? [];

        $orderData = $data['order'] ?? $data;

        $orderId = $orderData['order_id'] ?? ($orderData['id'] ?? ($data['order_id'] ?? ($data['id'] ?? null)));

        $orderNumber =
            $orderData['order_number'] ??
            ($orderData['number'] ??
                ($data['order_number'] ??
                    ($data['number'] ?? ($response['order_number'] ?? ($response['number'] ?? null)))));

        $status = $orderData['status'] ?? ($data['status'] ?? ($response['status'] ?? 'pending'));

        $totals = $orderData['totals'] ?? ($data['totals'] ?? ($response['totals'] ?? ($validated['totals'] ?? [])));

        $payloadValidation = $payload['client_validation'] ?? [];

        $grandTotal =
            $totals['grand_total'] ??
            ($totals['total'] ??
                ($orderData['grand_total'] ??
                    ($data['grand_total'] ??
                        ($response['grand_total'] ?? ($payloadValidation['grand_total'] ?? null)))));

        $deliveryFee =
            $totals['delivery_fee'] ??
            ($orderData['delivery_fee'] ??
                ($data['delivery_fee'] ?? ($response['delivery_fee'] ?? ($payloadValidation['delivery_fee'] ?? null))));

        $itemsTotal =
            $totals['items_total'] ??
            ($totals['subtotal'] ??
                ($orderData['items_total'] ??
                    ($data['items_total'] ??
                        ($response['items_total'] ?? ($payloadValidation['items_total'] ?? null)))));

        $paymentMethod =
            $orderData['payment_method'] ??
            ($data['payment_method'] ?? ($response['payment_method'] ?? ($payload['payment_method'] ?? 'cash')));

        $shop =
            $orderData['shop'] ??
            ($data['shop'] ?? ($response['shop'] ?? ($validated['shop'] ?? ($payload['shop'] ?? []))));

        $shopName =
            $shop['name'] ??
            ($orderData['shop_name'] ?? ($data['shop_name'] ?? ($response['shop_name'] ?? 'deinem Kiosk')));

        $address =
            $orderData['delivery_address'] ??
            ($data['delivery_address'] ?? ($response['delivery_address'] ?? ($payload['delivery_address'] ?? [])));

        $items =
            $orderData['items'] ??
            ($data['items'] ?? ($response['items'] ?? ($validated['items'] ?? ($payload['items'] ?? []))));

        $formatMoney = function ($value): string {
            if ($value === null || $value === '') {
                return '—';
            }

            return number_format((float) $value, 2, ',', '.') . ' €';
        };

        $paymentLabel = match ($paymentMethod) {
            'cash' => 'Barzahlung bei Lieferung',
            default => ucfirst((string) $paymentMethod),
        };

        $statusLabel = match ($status) {
            'pending' => 'Eingegangen',
            'accepted' => 'Angenommen',
            'preparing' => 'In Vorbereitung',
            'delivering' => 'Unterwegs',
            'completed' => 'Abgeschlossen',
            'cancelled' => 'Storniert',
            default => 'Eingegangen',
        };
    @endphp

    <main class="checkout-page thank-you-page">
        <section class="container thank-you-layout">
            <div class="thank-you-main">
                <div class="thank-you-hero">
                    <div class="thank-you-check" aria-hidden="true">
                        ✓
                    </div>

                    <p class="eyebrow">Bestellung erhalten</p>

                    <h1>Danke, deine Bestellung ist angekommen.</h1>

                    <p class="checkout-subline">
                        Wir haben deine Bestellung an {{ $shopName }} übermittelt.
                        Der Kiosk prüft sie jetzt und bereitet sie vor.
                    </p>

                    <div class="thank-you-actions">
                        <a href="{{ route('shops.selection') }}" class="thank-you-secondary-link">
                            Weiter einkaufen
                        </a>
                    </div>
                </div>

                <div class="thank-you-card">
                    <h2>Was passiert jetzt?</h2>

                    <div class="thank-you-steps">
                        <div class="thank-you-step is-active">
                            <span>1</span>
                            <div>
                                <strong>Bestellung eingegangen</strong>
                                <p>Deine Bestellung wurde erfolgreich an den Kiosk übertragen.</p>
                            </div>
                        </div>

                        <div class="thank-you-step">
                            <span>2</span>
                            <div>
                                <strong>Kiosk bereitet vor</strong>
                                <p>Der Kiosk stellt deine Artikel zusammen und macht die Lieferung fertig.</p>
                            </div>
                        </div>

                        <div class="thank-you-step">
                            <span>3</span>
                            <div>
                                <strong>Lieferung</strong>
                                <p>Der Fahrer bringt deine Bestellung zur angegebenen Adresse.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="thank-you-card thank-you-note-card">
                    <h2>Hinweis</h2>
                    <p>
                        Bitte halte dein Telefon erreichbar, falls der Kiosk oder Fahrer Rückfragen hat.
                        Bei Barzahlung bezahlst du direkt bei Lieferung.
                    </p>
                </div>
            </div>

            <aside class="thank-you-summary">
                <div class="checkout-summary-sticky">
                    <div class="checkout-card">
                        <div class="checkout-summary-head">
                            <div>
                                <p class="eyebrow">Bestellübersicht</p>
                                <h2>Deine Bestellung</h2>
                            </div>

                            <span>{{ $statusLabel }}</span>
                        </div>

                        <div class="thank-you-facts">
                            @if ($orderNumber)
                                <div>
                                    <span>Bestellnummer</span>
                                    <strong>{{ $orderNumber }}</strong>
                                </div>
                            @elseif ($orderId)
                                <div>
                                    <span>Bestellung</span>
                                    <strong>#{{ $orderId }}</strong>
                                </div>
                            @endif

                            <div>
                                <span>Kiosk</span>
                                <strong>{{ $shopName }}</strong>
                            </div>

                            <div>
                                <span>Zahlung</span>
                                <strong>{{ $paymentLabel }}</strong>
                            </div>
                        </div>

                        <div class="checkout-totals thank-you-totals">
                            @if ($itemsTotal !== null)
                                <div>
                                    <span>Artikel</span>
                                    <strong>{{ $formatMoney($itemsTotal) }}</strong>
                                </div>
                            @endif

                            @if ($deliveryFee !== null)
                                <div>
                                    <span>Lieferung</span>
                                    <strong>{{ $formatMoney($deliveryFee) }}</strong>
                                </div>
                            @endif

                            <div class="checkout-total-final">
                                <span>Gesamt</span>
                                <strong>{{ $formatMoney($grandTotal) }}</strong>
                            </div>
                        </div>

                        @if (!empty($address))
                            <div class="thank-you-address">
                                <span>Lieferadresse</span>

                                <strong>
                                    {{ $address['street'] ?? '' }} {{ $address['house_number'] ?? '' }}
                                </strong>

                                <p>
                                    {{ $address['postal_code'] ?? '' }} {{ $address['city'] ?? '' }}
                                </p>

                                @if (!empty($address['note']))
                                    <small>{{ $address['note'] }}</small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </section>
    </main>

    <x-marketing.footer />
</body>

</html>
