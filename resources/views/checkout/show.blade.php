<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kasse · Kioskheld</title>

    @vite(['resources/css/marketing.css', 'resources/js/marketing-home.js'])
</head>

<body>
    <x-marketing.nav />

    @php
        /*
         |--------------------------------------------------------------------------
         | Validated Cart normalisieren
         |--------------------------------------------------------------------------
         | Ziel:
         | - JustDeliver bleibt die Wahrheit.
         | - Die View versucht zuerst echte validierte Daten zu lesen.
         | - Falls die Struktur noch leicht anders ist, gibt es Fallbacks.
         */

        $validatedCart = $cart['validated'] ?? ($validated ?? $cart);

        $displayItems = $validatedCart['items'] ?? ($items ?? ($cart['items'] ?? []));

        $displayTotals = $validatedCart['totals'] ?? ($totals ?? []);

        $deliveryLocation = $validatedCart['delivery']['location'] ?? [];

        $customer = $customer ?? [];

        $paymentCapabilities = $validatedCart['payment_capabilities'] ?? ($paymentCapabilities ?? []);

        $directPaymentMethods = collect($paymentCapabilities)
            ->filter(function ($capability, string $method) {
                if (($capability['available'] ?? false) !== true) {
                    return false;
                }

                if ($method === 'paypal') {
                    return true;
                }

                return ($capability['direct_order_supported'] ?? false) === true;
            })
            ->keys()
            ->values()
            ->all();

        if (empty($directPaymentMethods)) {
            $directPaymentMethods = ['cash'];
        }

        $paymentLabels = [
            'cash' => [
                'title' => 'Barzahlung bei Lieferung',
                'description' => 'Du bezahlst direkt beim Fahrer.',
            ],
            'card' => [
                'title' => 'Kartenzahlung bei Lieferung',
                'description' => 'Du bezahlst bequem per Karte bei Lieferung.',
            ],
            'paypal' => [
                'title' => 'PayPal',
                'description' => 'PayPal benötigt einen separaten Zahlungsablauf.',
            ],
        ];

        $formatMoney = function ($value): string {
            return number_format((float) $value, 2, ',', '.') . ' €';
        };

        $getItemName = function (array $item): string {
            if (($item['type'] ?? 'product') === 'menu') {
                return $item['name'] ??
                    ($item['menu_name'] ?? ($item['title'] ?? 'Sparpaket #' . ($item['menu_id'] ?? '')));
            }

            return $item['name'] ??
                ($item['product_name'] ??
                    ($item['variant_name'] ?? ($item['title'] ?? 'Produkt #' . ($item['variant_id'] ?? ''))));
        };

        $getVariantText = function (array $item): ?string {
            if (($item['type'] ?? 'product') === 'menu') {
                return null;
            }

            return $item['variant_name'] ?? ($item['unit_name'] ?? null);
        };

        $getQuantity = function (array $item): int {
            return (int) ($item['quantity'] ?? ($item['qty'] ?? 1));
        };

        $getLineTotal = function (array $item) {
            return $item['line_total'] ?? ($item['total'] ?? ($item['total_price'] ?? null));
        };

        $getUnitPrice = function (array $item) {
            return $item['unit_price'] ?? ($item['price'] ?? ($item['item_price'] ?? null));
        };

        $getChoices = function (array $item): array {
            return $item['choices'] ?? ($item['selected_options'] ?? ($item['options'] ?? []));
        };

        $itemsTotal =
            $displayTotals['items_total'] ?? ($displayTotals['subtotal'] ?? ($displayTotals['products_total'] ?? 0));

        $deliveryFee =
            $displayTotals['delivery_fee'] ??
            ($displayTotals['shipping_total'] ?? ($displayTotals['delivery_cost'] ?? 0));

        $customerFee = $displayTotals['customer_fee'] ?? 0;

        $paymentFee = $displayTotals['payment_fee'] ?? 0;

        $paymentFeeLabel = $displayTotals['payment_fee_label'] ?? 'Zahlungsgebühr';

        $tipAmount = $displayTotals['tip_amount'] ?? 0;

        $grandTotal =
            $displayTotals['grand_total'] ?? ($displayTotals['total'] ?? ($displayTotals['order_total'] ?? 0));

        $minimumOrderValue = $displayTotals['minimum_order_value'] ?? ($validatedCart['minimum_order_value'] ?? null);

        $missingMinimumOrderValue = $displayTotals['missing_minimum_order_value'] ?? null;

        $shopName = $validatedCart['shop']['name'] ?? ($validatedCart['shop_name'] ?? ($cart['shop_name'] ?? null));

        $postcode = $deliveryLocation['postal_code'] ?? ($cart['postcode'] ?? '');

        $city = $deliveryLocation['city'] ?? ($cart['city'] ?? '');
    @endphp

    <main class="checkout-page">
        <section class="container checkout-layout">
            <div class="checkout-main">
                <div class="checkout-hero-card">
                    <p class="eyebrow">Kasse</p>

                    <h1>Fast geschafft.</h1>

                    <p class="checkout-subline">
                        Dein Warenkorb wurde geprüft. Gib jetzt deine Lieferdaten ein.
                    </p>

                    @if ($shopName)
                        <div class="checkout-shop-pill">
                            Lieferung von <strong>{{ $shopName }}</strong>
                        </div>
                    @endif
                </div>

                @if (session('status'))
                    <div class="checkout-alert checkout-alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="checkout-alert checkout-alert-error">
                        <strong>Bitte prüfe deine Angaben.</strong>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (!empty($customer))
                    <div class="checkout-alert checkout-alert-info">
                        Deine Lieferdaten sind gespeichert. Im nächsten Schritt senden wir die Bestellung an den Kiosk.
                    </div>
                @endif

                <form class="checkout-form" method="post" action="{{ route('checkout.customer.store') }}">
                    @csrf

                    <div class="checkout-card">
                        <div class="checkout-card-head">
                            <div>
                                <span class="checkout-step">1</span>
                            </div>

                            <div>
                                <h2>Kontaktdaten</h2>
                                <p>Damit der Fahrer dich bei Rückfragen erreichen kann.</p>
                            </div>
                        </div>

                        <div class="checkout-field-stack">
                            <label>
                                Name
                                <input type="text" name="customer_name" autocomplete="name"
                                    placeholder="Max Mustermann"
                                    value="{{ old('customer_name', $customer['customer_name'] ?? '') }}" required>
                            </label>

                            <div class="checkout-grid-2">
                                <label>
                                    Telefon
                                    <input type="tel" name="customer_phone" autocomplete="tel"
                                        placeholder="0176 12345678"
                                        value="{{ old('customer_phone', $customer['customer_phone'] ?? '') }}" required>
                                </label>

                                <label>
                                    E-Mail optional
                                    <input type="email" name="customer_email" autocomplete="email"
                                        placeholder="mail@example.de"
                                        value="{{ old('customer_email', $customer['customer_email'] ?? '') }}">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <div class="checkout-card-head">
                            <div>
                                <span class="checkout-step">2</span>
                            </div>

                            <div>
                                <h2>Lieferadresse</h2>
                                <p>PLZ und Ort kommen aus deiner geprüften Verfügbarkeit.</p>
                            </div>
                        </div>

                        <div class="checkout-field-stack">
                            <div class="checkout-grid-2 checkout-grid-wide-left">
                                <label>
                                    Straße
                                    <input type="text" name="street" autocomplete="address-line1"
                                        placeholder="Musterstraße"
                                        value="{{ old('street', $customer['street'] ?? '') }}" required>
                                </label>

                                <label>
                                    Hausnummer
                                    <input type="text" name="house_number" placeholder="12a"
                                        value="{{ old('house_number', $customer['house_number'] ?? '') }}" required>
                                </label>
                            </div>

                            <div class="checkout-grid-2">
                                <label>
                                    PLZ
                                    <input type="text" value="{{ $postcode }}" readonly>
                                </label>

                                <label>
                                    Ort
                                    <input type="text" value="{{ $city }}" readonly>
                                </label>
                            </div>

                            <label>
                                Hinweis optional
                                <textarea name="delivery_note" rows="3" placeholder="Etage, Klingelname, Wunschhinweis ...">{{ old('delivery_note', $customer['delivery_note'] ?? '') }}</textarea>
                            </label>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <div class="checkout-card-head">
                            <div>
                                <span class="checkout-step">3</span>
                            </div>

                            <div>
                                <h2>Zahlung</h2>
                                <p>Weitere Zahlungsarten können später über JustDeliver freigeschaltet werden.</p>
                            </div>
                        </div>

                        <div class="checkout-payment-options">
                            @foreach ($directPaymentMethods as $method)
                                @php
                                    $label = $paymentLabels[$method] ?? [
                                        'title' => ucfirst((string) $method),
                                        'description' => 'Diese Zahlungsart ist verfügbar.',
                                    ];

                                    $selectedPaymentMethod = old(
                                        'payment_method',
                                        $customer['payment_method'] ?? ($directPaymentMethods[0] ?? 'cash'),
                                    );
                                @endphp

                                <label class="checkout-radio">
                                    <input type="radio" name="payment_method" value="{{ $method }}"
                                        class="checkout-payment-radio" @checked($selectedPaymentMethod === $method)>

                                    <span>
                                        <strong>{{ $label['title'] }}</strong>
                                        <small>{{ $label['description'] }}</small>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit"
                        class="checkout-submit @if (!empty($customer)) checkout-submit-secondary @endif">
                        @if (!empty($customer))
                            Lieferdaten aktualisieren
                        @else
                            Lieferdaten prüfen
                        @endif
                    </button>
                </form>

            </div>

            <aside class="checkout-summary">
                <div class="checkout-summary-sticky">
                    <div class="checkout-card">
                        <div class="checkout-summary-head">
                            <div>
                                <p class="eyebrow">Übersicht</p>
                                <h2>Deine Bestellung</h2>
                            </div>

                            <span>{{ count($displayItems) }} Position(en)</span>
                        </div>

                        @if (empty($displayItems))
                            <div class="checkout-empty">
                                Dein geprüfter Warenkorb ist leer oder abgelaufen.
                            </div>
                        @else
                            <div class="checkout-items">
                                @foreach ($displayItems as $item)
                                    @php
                                        $quantity = $getQuantity($item);
                                        $itemName = $getItemName($item);
                                        $variantText = $getVariantText($item);
                                        $unitPrice = $getUnitPrice($item);
                                        $lineTotal = $getLineTotal($item);
                                        $choices = $getChoices($item);
                                    @endphp

                                    <div class="checkout-item">
                                        <div class="checkout-item-main">
                                            <strong>{{ $itemName }}</strong>

                                            @if ($variantText)
                                                <span>{{ $variantText }}</span>
                                            @endif

                                            @if (!empty($choices))
                                                <div class="checkout-choice-list">
                                                    @foreach ($choices as $choice)
                                                        @php
                                                            $choiceLabel = is_array($choice)
                                                                ? $choice['product_name'] ??
                                                                    ($choice['variant_name'] ??
                                                                        ($choice['name'] ??
                                                                            ($choice['label'] ??
                                                                                ($choice['option_name'] ??
                                                                                    ($choice['choice_name'] ?? null)))))
                                                                : $choice;

                                                            $choiceGroup = is_array($choice)
                                                                ? $choice['choice_group_label'] ??
                                                                    ($choice['group_name'] ??
                                                                        ($choice['choice_group_name'] ?? null))
                                                                : null;

                                                            $choiceQuantity = is_array($choice)
                                                                ? (int) ($choice['total_quantity'] ??
                                                                    ($choice['quantity'] ?? 1))
                                                                : 1;

                                                            $choiceLineTotal = is_array($choice)
                                                                ? $choice['line_total'] ?? null
                                                                : null;
                                                        @endphp

                                                        @if ($choiceLabel)
                                                            <small>
                                                                @if ($choiceGroup)
                                                                    {{ $choiceGroup }}:
                                                                @endif

                                                                {{ $choiceQuantity }}× {{ $choiceLabel }}

                                                                @if ($choiceLineTotal !== null && (float) $choiceLineTotal > 0)
                                                                    + {{ $formatMoney($choiceLineTotal) }}
                                                                @endif
                                                            </small>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="checkout-item-price">
                                            <span>{{ $quantity }}×</span>

                                            @if ($lineTotal !== null)
                                                <strong>{{ $formatMoney($lineTotal) }}</strong>
                                            @elseif ($unitPrice !== null)
                                                <strong>{{ $formatMoney((float) $unitPrice * $quantity) }}</strong>
                                            @else
                                                <strong>—</strong>
                                            @endif

                                            @if ($unitPrice !== null)
                                                <small>{{ $formatMoney($unitPrice) }} / Stück</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="checkout-totals">
                            <div>
                                <span>Artikel</span>
                                <strong>{{ $formatMoney($itemsTotal) }}</strong>
                            </div>

                            <div>
                                <span>Lieferung</span>
                                <strong>{{ $formatMoney($deliveryFee) }}</strong>
                            </div>

                            @if ((float) $customerFee > 0)
                                <div>
                                    <span>Servicegebühr</span>
                                    <strong>{{ $formatMoney($customerFee) }}</strong>
                                </div>
                            @endif

                            @if ((float) $paymentFee > 0)
                                <div>
                                    <span>{{ $paymentFeeLabel }}</span>
                                    <strong>{{ $formatMoney($paymentFee) }}</strong>
                                </div>
                            @endif

                            @if ((float) $tipAmount > 0)
                                <div>
                                    <span>Trinkgeld</span>
                                    <strong>{{ $formatMoney($tipAmount) }}</strong>
                                </div>
                            @endif

                            @if ($minimumOrderValue !== null)
                                <div>
                                    <span>Mindestbestellwert</span>
                                    <strong>{{ $formatMoney($minimumOrderValue) }}</strong>
                                </div>
                            @endif

                            <div class="checkout-total-final">
                                <span>Gesamt</span>
                                <strong>{{ $formatMoney($grandTotal) }}</strong>
                            </div>
                        </div>

                        <div class="checkout-summary-note">
                            Preise, Lieferkosten und Verfügbarkeit wurden durch JustDeliver geprüft.
                        </div>
                    </div>


                    @if (!empty($customer))
                        <div class="checkout-final-card">
                            <div>
                                <strong>Bereit zur Bestellung</strong>
                                <span>Deine Lieferdaten und dein Warenkorb sind geprüft.</span>
                            </div>

                            @php
                                $selectedFinalPaymentMethod =
                                    $customer['payment_method'] ?? ($cart['payment_method'] ?? 'cash');
                            @endphp

                            <form method="post"
                                action="{{ $selectedFinalPaymentMethod === 'paypal' ? route('checkout.paypal.create') : route('checkout.order.store') }}">
                                @csrf

                                <button type="submit" class="checkout-order-submit">
                                    @if ($selectedFinalPaymentMethod === 'paypal')
                                        Mit PayPal bezahlen
                                    @else
                                        Jetzt verbindlich bestellen
                                    @endif
                                </button>
                            </form>

                            <p class="checkout-legal-note">
                                @if ($selectedFinalPaymentMethod === 'paypal')
                                    Du wirst zu PayPal weitergeleitet. Die Bestellung wird nach erfolgreicher Zahlung
                                    abgeschlossen.
                                @else
                                    Mit Klick auf den Button gibst du eine verbindliche Bestellung beim Kiosk auf.
                                    Preise, Lieferkosten und Verfügbarkeit wurden zuvor geprüft.
                                @endif
                            </p>
                        </div>
                    @endif



                </div>
            </aside>
        </section>
    </main>

    <x-marketing.footer />
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkoutForm = document.querySelector('.checkout-form');
        const paymentRadios = document.querySelectorAll('.checkout-payment-radio');

        if (!checkoutForm || paymentRadios.length === 0) {
            return;
        }

        const hasSavedCustomer = @json(!empty($customer));

        paymentRadios.forEach((radio) => {
            radio.addEventListener('change', () => {
                if (!hasSavedCustomer) {
                    return;
                }

                checkoutForm.submit();
            });
        });
    });
</script>

</html>
