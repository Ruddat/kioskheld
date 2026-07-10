@extends('layouts.marketing')

@section('title', ($shop['name'] ?? 'Kioskheld') . ' – Snacks & Getränke geliefert')

@section('meta_description', 'Bestelle Snacks, Getränke, Süßes und Kiosk-Bundles bei ' . ($shop['name'] ?? 'Kioskheld')
    . ' direkt über Kioskheld.')

@section('content')
    @php
        $rule = $deliveryRule ?? [];
        $menus = $menus ?? [];
        $sections = $sections ?? [];
        $productsByCategoryId = $productsByCategoryId ?? [];

        $estimatedMinutes = $rule['estimated_delivery_minutes'] ?? null;
        $minimumOrderValue = $rule['min_order_value'] ?? null;
        $deliveryFee = $rule['delivery_fee'] ?? null;
        $freeDeliveryFrom = $rule['free_delivery_from'] ?? null;
    @endphp

    <main class="shop-app-page" data-shop-id="{{ $shop['id'] ?? '' }}" data-postcode="{{ $postcode ?? '' }}">
        <div class="shop-app-nav-wrap">
            <x-marketing.nav />
        </div>

<section class="shop-app-hero">
    <div class="shop-app-hero-bg" aria-hidden="true"></div>

    <div class="container shop-app-hero-inner">
        <a href="{{ route('shops.selection') }}" class="shop-app-back">
            <span>←</span>
            Kioskauswahl
        </a>

        <div class="shop-app-hero-grid">
            <div class="shop-app-hero-content">
                <p class="shop-powered">Kioskheld Shop</p>

                <h1>
                    <span>{{ $shop['name'] ?? 'Kioskheld' }}</span>
                    <strong>direkt zu dir.</strong>
                </h1>

                <p class="shop-hero-subline">
                    Snacks, Getränke, Süßes, Eis und Kiosk-Bundles online bestellen.
                    Dein Warenkorb wird vor dem Checkout noch einmal geprüft.
                </p>

                <div class="shop-open-status">
                    <span></span>
                    Geöffnet
                </div>

                <div class="shop-hero-facts">
                    @if ($estimatedMinutes)
                        <div>
                            <small>Lieferzeit</small>
                            <strong>{{ $estimatedMinutes }} Min.</strong>
                        </div>
                    @endif

                    @if ($minimumOrderValue)
                        <div>
                            <small>Mindestbestellwert</small>
                            <strong>ab {{ number_format((float) $minimumOrderValue, 2, ',', '.') }} €</strong>
                        </div>
                    @endif

                    @if ($deliveryFee !== null)
                        <div>
                            <small>Liefergebühr</small>
                            <strong>{{ number_format((float) $deliveryFee, 2, ',', '.') }} €</strong>
                        </div>
                    @endif

                    @if ($freeDeliveryFrom)
                        <div>
                            <small>Kostenlos ab</small>
                            <strong>{{ number_format((float) $freeDeliveryFrom, 2, ',', '.') }} €</strong>
                        </div>
                    @endif
                </div>

                @if (!empty($postcode))
                    <div class="shop-app-postcode">
                        <span>📍</span>
                        Lieferung nach <strong>PLZ {{ $postcode }}</strong>
                    </div>
                @endif
            </div>

            <div class="shop-app-hero-card">
                <div class="shop-app-hero-card-inner">
                    <span class="shop-app-hero-badge">Schnell bestellen</span>

                    <h2>Kiosk-Produkte ohne Umweg.</h2>

                    <p>
                        Sortiment ansehen, Produkte wählen und direkt zur Kasse.
                        Preise und Lieferbarkeit werden serverseitig geprüft.
                    </p>

                    <a href="#catalog" class="shop-app-hero-button">
                        Sortiment ansehen
                        <span>↓</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

        <section class="shop-app-content" id="catalog">
            <div class="container">
                <div class="shop-search">
                    <input type="search" id="productSearch" placeholder="Produkte suchen...">
                    <span>⌕</span>
                </div>

                @if (!empty($groupedCatalog) || !empty($menus))
                    <div class="shop-category-icons">
                        @if (!empty($menus))
                            <a href="#menus">
                                <span class="category-icon">📦</span>
                                <strong>Sparpakete</strong>
                            </a>
                        @endif

                        @foreach ($groupedCatalog as $categoryName => $products)
                            <a href="#category-{{ Str::slug($categoryName) }}">
                                <span class="category-icon">
                                    @php
                                        $icon = match (true) {
                                            str_contains(strtolower($categoryName), 'getränk') => '🥤',
                                            str_contains(strtolower($categoryName), 'energy') => '⚡',
                                            str_contains(strtolower($categoryName), 'chips') => '🍟',
                                            str_contains(strtolower($categoryName), 'snack') => '🍟',
                                            str_contains(strtolower($categoryName), 'süß') => '🍬',
                                            str_contains(strtolower($categoryName), 'eis') => '🍦',
                                            str_contains(strtolower($categoryName), 'bundle') => '📦',
                                            default => '⭐',
                                        };
                                    @endphp

                                    {{ $icon }}
                                </span>

                                <strong>{{ $categoryName }}</strong>
                            </a>
                        @endforeach
                    </div>
                @endif

                @if (empty($catalog) && empty($menus))
                    <div class="shop-empty-state">
                        Für diesen Shop sind aktuell noch keine Produkte verfügbar.
                    </div>
                @else
                    <div class="shop-category-stack">

                        @if (!empty($menus))
                            <section class="shop-product-section" id="menus">
                                <div class="shop-section-head">
                                    <h2>Sparpakete & Menüs</h2>
                                    <a href="#menus">Alle anzeigen</a>
                                </div>

                                <div class="shop-product-row">
                                    @foreach ($menus as $menu)
                                        @php
                                            $menuPrice =
                                                $menu['payable_price'] ??
                                                ($menu['effective_price'] ?? ($menu['menu_price'] ?? null));

                                            $imageUrl = $menu['image_url'] ?? null;
                                            $requiresChoices = ($menu['requires_choices'] ?? false) === true;
                                            $isActive = ($menu['active'] ?? false) === true;
                                        @endphp

                                        <article
                                            class="shop-product-card shop-menu-card {{ !$isActive ? 'is-disabled' : '' }}">
                                            <div class="shop-product-image">
                                                @if ($imageUrl)
                                                    <img src="{{ $imageUrl }}" alt="{{ $menu['name'] ?? 'Menübild' }}"
                                                        loading="lazy">
                                                @else
                                                    <span>{{ mb_substr($menu['name'] ?? 'M', 0, 1) }}</span>
                                                @endif
                                            </div>

                                            <div class="shop-product-body">
                                                <h3>{{ $menu['name'] ?? 'Menü' }}</h3>

                                                @if (!empty($menu['description']))
                                                    <p class="shop-menu-description">
                                                        {{ $menu['description'] }}
                                                    </p>
                                                @endif

                                                <div class="shop-product-bottom">
                                                    <strong>
                                                        @if ($menuPrice !== null)
                                                            {{ number_format((float) $menuPrice, 2, ',', '.') }} €
                                                        @else
                                                            Preis folgt
                                                        @endif
                                                    </strong>

                                                    <button type="button" class="shop-product-plus add-menu-to-cart"
                                                        @disabled(!$isActive) data-menu-id="{{ $menu['id'] ?? '' }}"
                                                        data-menu-name="{{ $menu['name'] ?? '' }}"
                                                        data-price="{{ $menuPrice ?? '' }}"
                                                        data-image-url="{{ $imageUrl ?? '' }}"
                                                        data-requires-choices="{{ $requiresChoices ? '1' : '0' }}">
                                                        +
                                                    </button>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        @endif



                        @foreach ($groupedCatalog as $categoryName => $products)
                            <section class="shop-product-section" id="category-{{ Str::slug($categoryName) }}">
                                <div class="shop-section-head">
                                    <h2>{{ $categoryName }}</h2>
                                    <a href="#category-{{ Str::slug($categoryName) }}">Alle anzeigen</a>
                                </div>

                                <div class="shop-product-row">
                                    @foreach ($products as $product)
                                        @php
                                            $variant = $product['variants'][0] ?? null;

                                            $price = $variant['price'] ?? ($product['lowest_price'] ?? null);

                                            $imageUrl = $product['image_url'] ?? null;
                                            $hasPlaceholderImage =
                                                $imageUrl && str_contains($imageUrl, 'no-image-placeholder');
                                            $variantId = $variant['id'] ?? null;

                                            $productAvailable = ($product['is_available'] ?? false) === true;
                                            $variantAvailable = ($variant['is_available'] ?? true) === true;

                                            $isAvailable = $productAvailable && $variantAvailable;
                                            $availableQuantity = $variant['available_quantity'] ?? null;
                                        @endphp

                                        <article class="shop-product-card {{ !$isAvailable ? 'is-disabled' : '' }}">
                                            <div class="shop-product-image">
                                                @if ($imageUrl && !$hasPlaceholderImage)
                                                    <img src="{{ $imageUrl }}"
                                                        alt="{{ $product['name'] ?? 'Produktbild' }}" loading="lazy">
                                                @else
                                                    <span>{{ mb_substr($product['name'] ?? 'P', 0, 1) }}</span>
                                                @endif
                                            </div>

                                            <div class="shop-product-body">
                                                <h3>{{ $product['name'] ?? 'Produkt' }}</h3>

                                                <div class="shop-product-bottom">
                                                    <strong>
                                                        @if ($price !== null)
                                                            {{ number_format((float) $price, 2, ',', '.') }} €
                                                        @else
                                                            Preis folgt
                                                        @endif
                                                    </strong>

                                                    <button type="button" class="shop-product-plus add-to-cart"
                                                        @disabled(!$isAvailable || empty($variantId)) data-variant-id="{{ $variantId }}"
                                                        data-product-id="{{ $product['id'] ?? '' }}"
                                                        data-product-name="{{ $product['name'] ?? '' }}"
                                                        data-price="{{ $price ?? '' }}"
                                                        data-image-url="{{ $imageUrl ?? '' }}"
                                                        data-is-available="{{ $isAvailable ? '1' : '0' }}"
                                                        data-available-quantity="{{ $availableQuantity ?? '' }}">
                                                        +
                                                    </button>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <button type="button" class="shop-cart-bar" id="cartFloatingButton">
            <span class="shop-cart-icon">
                🛒
                <em id="cartFloatingCount">0</em>
            </span>

            <span>
                <strong>Warenkorb</strong>
                <small id="cartFloatingText">Noch keine Artikel</small>
            </span>

            <b id="cartFloatingTotal">0,00 €</b>
        </button>

        <aside class="cart-drawer" id="cartDrawer" aria-hidden="true">
            <div class="cart-drawer-panel">
                <div class="cart-head">
                    <div>
                        <p class="eyebrow">Deine Bestellung</p>
                        <h2>Warenkorb</h2>
                    </div>

                    <button type="button" class="cart-close" id="cartCloseButton">
                        ×
                    </button>
                </div>

                <div class="cart-items" id="cartItems">
                    <div class="cart-empty">
                        Dein Warenkorb ist noch leer.
                    </div>
                </div>

                <div class="cart-summary">
                    <div class="cart-subtotal-row">
                        <span>Zwischensumme</span>
                        <strong id="cartSubtotal">0,00 €</strong>
                    </div>

                    <div class="cart-validation-message" id="cartValidationMessage" hidden></div>

                    <div class="cart-validated-totals" id="cartValidatedTotals" hidden>
                        <div>
                            <span>Artikel</span>
                            <strong id="cartValidatedItemsTotal">0,00 €</strong>
                        </div>

                        <div>
                            <span>Liefergebühr</span>
                            <strong id="cartValidatedDeliveryFee">0,00 €</strong>
                        </div>

                        <div class="cart-total-row">
                            <span>Gesamt</span>
                            <strong id="cartValidatedGrandTotal">0,00 €</strong>
                        </div>

                        <div class="cart-minimum-row" id="cartMinimumRow" hidden>
                            <span>Mindestbestellwert</span>
                            <strong id="cartValidatedMinimum">0,00 €</strong>
                        </div>

                        <div class="cart-missing-row" id="cartMissingRow" hidden>
                            <span>Es fehlen noch</span>
                            <strong id="cartValidatedMissing">0,00 €</strong>
                        </div>
                    </div>

                    <p id="cartSummaryHint">
                        Preise, Lieferbarkeit und Mindestbestellwert werden vor dem Checkout noch einmal serverseitig
                        geprüft.
                    </p>

                    <button type="button" class="cart-checkout-button" id="cartCheckoutButton" disabled>
                        Weiter zur Kasse
                    </button>
                </div>
            </div>
        </aside>

        <div class="cart-backdrop" id="cartBackdrop"></div>

        <aside class="menu-choice-drawer" id="menuChoiceDrawer" aria-hidden="true">
            <div class="menu-choice-panel">
                <div class="menu-choice-head">
                    <div>
                        <p class="eyebrow">Sparpaket konfigurieren</p>
                        <h2 id="menuChoiceTitle">Menü</h2>
                    </div>

                    <button type="button" class="menu-choice-close" id="menuChoiceCloseButton">
                        ×
                    </button>
                </div>

                <div class="menu-choice-body">
                    <div class="menu-choice-intro">
                        <p id="menuChoiceDescription"></p>
                        <strong id="menuChoicePrice">0,00 €</strong>
                    </div>

                    <div class="menu-choice-groups" id="menuChoiceGroups"></div>

                    <div class="menu-choice-message" id="menuChoiceMessage" hidden></div>
                </div>

                <div class="menu-choice-footer">
                    <button type="button" class="menu-choice-add-button" id="menuChoiceAddButton">
                        In den Warenkorb
                    </button>
                </div>
            </div>
        </aside>

        <div class="menu-choice-backdrop" id="menuChoiceBackdrop"></div>


        <x-marketing.footer />
    </main>

<script>
    window.KioskheldShop = {
        cartValidateUrl: @json(route('cart.validate')),
        csrfToken: @json(csrf_token()),
        shopId: @json($shop['id'] ?? null),
        postcode: @json($postcode ?? null),
        catalog: @json($catalog),
        menus: @json($menus),
        productsByCategoryId: @json($productsByCategoryId),
    };
</script>

@vite(['resources/js/shop-cart.js'])



@endsection
