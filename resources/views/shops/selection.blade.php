@extends('layouts.marketing')

@section('title', 'Kioske in deiner Nähe – Kioskheld')

@section('content')
    <main class="shop-selection-page">
        <section class="shop-selection-shell">
            <div class="shop-selection-topbar">
                <x-marketing.logo :show-crown="true" />

                <button class="shop-selection-menu" type="button" aria-label="Menü öffnen">
                    ☰
                </button>
            </div>

            <div class="shop-selection-inner">
                <a href="{{ route('home') }}" class="shop-selection-back">
                    <span>←</span>
                    Neue Suche
                </a>

                <header class="shop-selection-header">
                    <h1>Kioskhelds in deiner Nähe</h1>

                    <p>
                        Wir haben {{ count($shops) }}
                        {{ count($shops) === 1 ? 'Kiosk' : 'Kioske' }} gefunden, die zu dir liefern.
                    </p>

                    <div class="shop-selection-postcode">
                        <span>📍</span>
                        <strong>PLZ {{ $postcode }}</strong>

                        @if(!empty($district))
                            <small>{{ $district }}</small>
                        @endif
                    </div>
                </header>

                <div class="shop-selection-list">
                    @foreach($shops as $index => $shop)
                        @php
                            $rule = $shop['delivery_rule'] ?? [];
                            $area = $shop['delivery_area'] ?? [];

                            $minutes = $rule['estimated_delivery_minutes'] ?? null;
                            $minOrder = $rule['min_order_value'] ?? null;
                            $fee = $rule['delivery_fee'] ?? null;
                            $freeFrom = $rule['free_delivery_from'] ?? null;

                            $tags = [];

                            if ($index === 0) {
                                $tags[] = 'Beliebtester Kiosk';
                            }

                            if (!empty($area['name'])) {
                                $tags[] = $area['name'];
                            }

                            if ($freeFrom) {
                                $tags[] = 'Gratis Lieferung ab ' . number_format((float) $freeFrom, 2, ',', '.') . ' €';
                            }
                        @endphp

                        <article class="shop-card">
                            <div class="shop-card-image">
                                @if($index === 0)
                                    <span class="shop-card-badge">★ Beliebtester Kiosk</span>
                                @endif

                                <div class="shop-card-image-placeholder">
                                    <span>Kiosk</span>
                                </div>
                            </div>

                            <div class="shop-card-content">
                                <div class="shop-card-head">
                                    <div>
                                        <h2>{{ $shop['name'] ?? 'Kioskheld Kiosk' }}</h2>

                                        <p class="shop-card-location">
                                            {{ trim(($shop['zip'] ?? '') . ' ' . ($shop['city'] ?? '')) }}
                                        </p>
                                    </div>

                                    <span class="shop-card-status">
                                        <span></span>
                                        Geöffnet
                                    </span>
                                </div>

                                <div class="shop-card-meta">
                                    @if($minutes)
                                        <span>⏱ {{ $minutes }} Min.</span>
                                    @endif

                                    @if($minOrder)
                                        <span>🛒 ab {{ number_format((float) $minOrder, 2, ',', '.') }} €</span>
                                    @endif

                                    @if($fee !== null)
                                        <span>🚚 {{ number_format((float) $fee, 2, ',', '.') }} € Lieferung</span>
                                    @endif
                                </div>

                                <p class="shop-card-description">
                                    Getränke, Snacks, Süßes, Eis & mehr
                                </p>

                                @if(count($tags))
                                    <div class="shop-card-tags">
                                        @foreach(array_slice($tags, 0, 3) as $tag)
                                            <span>{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <a href="{{ route('shops.show', ['shopSlug' => $shop['slug']]) }}"
                                   class="shop-card-button">
                                    Zum Shop
                                    <span>›</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <section class="shop-selection-missing">
                    <div class="shop-selection-missing-icon">
                        🛵
                    </div>

                    <div>
                        <h2>Keinen passenden Kiosk gefunden?</h2>
                        <p>
                            Trag dich ein und wir informieren dich, wenn ein Kioskheld in deiner Nähe startet.
                        </p>
                    </div>

                    <a href="{{ route('home') }}#partner">
                        Jetzt eintragen
                    </a>
                </section>

                <section class="shop-selection-benefits">
                    <div>
                        <strong>⏱</strong>
                        <span>Schnell geliefert</span>
                        <small>in 25–50 Minuten</small>
                    </div>

                    <div>
                        <strong>💳</strong>
                        <span>Bequem bezahlen</span>
                        <small>Online & sicher</small>
                    </div>

                    <div>
                        <strong>⭐</strong>
                        <span>Top Auswahl</span>
                        <small>Snacks, Getränke & mehr</small>
                    </div>

                    <div>
                        <strong>🛡</strong>
                        <span>Klare Gebühren</span>
                        <small>keine Überraschung</small>
                    </div>
                </section>
            </div>
        </section>
    </main>
@endsection
