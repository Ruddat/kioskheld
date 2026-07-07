@extends('layouts.marketing')

@section('title', 'Kiosk auswählen – Kioskheld')

@section('content')
    <main class="shop-selection-page">
        <section class="shop-selection-hero">
            <div class="container">
                <p class="eyebrow">Kioskheld in deiner Nähe</p>

                <h1>Wähle deinen Kiosk</h1>

                <p class="shop-selection-subline">
                    Für die Postleitzahl <strong>{{ $postcode }}</strong>
                    @if($district)
                        im Ortsteil <strong>{{ $district }}</strong>
                    @endif
                    haben wir mehrere verfügbare Kioske gefunden.
                </p>
            </div>
        </section>

        <section class="shop-selection-list-section">
            <div class="container">
                <div class="shop-selection-grid">
                    @foreach($shops as $shop)
                        @php
                            $rule = $shop['delivery_rule'] ?? [];
                            $area = $shop['delivery_area'] ?? [];
                        @endphp

                        <article class="shop-selection-card">
                            <div class="shop-selection-card-header">
                                <div>
                                    <h2>{{ $shop['name'] ?? 'Kiosk' }}</h2>

                                    <p>
                                        {{ $shop['zip'] ?? '' }}
                                        {{ $shop['city'] ?? '' }}
                                    </p>
                                </div>

                                <span class="shop-selection-badge">
                                    verfügbar
                                </span>
                            </div>

                            @if(!empty($area['name']))
                                <p class="shop-selection-area">
                                    Liefergebiet: {{ $area['name'] }}
                                </p>
                            @endif

                            <div class="shop-selection-facts">
                                @if(isset($rule['estimated_delivery_minutes']))
                                    <div>
                                        <span>Lieferzeit</span>
                                        <strong>ca. {{ $rule['estimated_delivery_minutes'] }} Min.</strong>
                                    </div>
                                @endif

                                @if(isset($rule['min_order_value']))
                                    <div>
                                        <span>Mindestbestellwert</span>
                                        <strong>{{ number_format((float) $rule['min_order_value'], 2, ',', '.') }} €</strong>
                                    </div>
                                @endif

                                @if(isset($rule['delivery_fee']))
                                    <div>
                                        <span>Liefergebühr</span>
                                        <strong>{{ number_format((float) $rule['delivery_fee'], 2, ',', '.') }} €</strong>
                                    </div>
                                @endif

                                @if(isset($rule['free_delivery_from']) && $rule['free_delivery_from'])
                                    <div>
                                        <span>Kostenlos ab</span>
                                        <strong>{{ number_format((float) $rule['free_delivery_from'], 2, ',', '.') }} €</strong>
                                    </div>
                                @endif
                            </div>

                            <a class="shop-selection-button"
                               href="{{ route('shops.show', ['shopSlug' => $shop['slug']]) }}">
                                Diesen Kiosk öffnen
                            </a>
                        </article>
                    @endforeach
                </div>

                <div class="shop-selection-back">
                    <a href="{{ route('home') }}">Andere Postleitzahl prüfen</a>
                </div>
            </div>
        </section>
    </main>
@endsection
