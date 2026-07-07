@extends('layouts.marketing')

@section('title', ($shop['name'] ?? 'Kioskheld') . ' – Snacks & Getränke geliefert')

@section('meta_description',
    'Bestelle Snacks, Getränke, Süßes und Kiosk-Bundles bei ' . ($shop['name'] ?? 'Kioskheld') . ' direkt über Kioskheld.'
)

@section('content')
    <main
        class="shop-app-page"
        data-shop-id="{{ $shop['id'] ?? '' }}"
        data-postcode="{{ $postcode ?? '' }}"
    >
        <section class="shop-app-hero">
            <div class="shop-app-hero-bg"></div>

            <div class="container shop-app-hero-inner">
                <div class="shop-app-topbar">
                    <a href="{{ url('/') }}" class="shop-app-logo">
                        KIOSK<span>HELD</span>
                    </a>

                    <button type="button" class="shop-app-menu" aria-label="Menü öffnen">
                        ☰
                    </button>
                </div>

                <div class="shop-app-hero-content">
                    <p class="shop-powered">Powered by Foodzwerge</p>

                    <h1>{{ $shop['name'] ?? 'Kioskheld' }}</h1>

                    <div class="shop-open-status">
                        <span></span>
                        Geöffnet · bis 23:00 Uhr
                    </div>

                    <div class="shop-hero-facts">
                        <div>
                            <strong>25–40 Min.</strong>
                        </div>

                        <div>
                            <strong>ab 15,00 €</strong>
                        </div>

                        <div>
                            <strong>2,99 € Liefergebühr</strong>
                        </div>

                        <div>
                            <strong>4,8 ★</strong>
                            <span>(248)</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="shop-app-content">
            <div class="container">
                <div class="shop-search">
                    <input type="search" id="productSearch" placeholder="Produkte suchen...">
                    <span>⌕</span>
                </div>

                <div class="shop-category-icons">
                    @foreach($groupedCatalog as $categoryName => $products)
                        <a href="#category-{{ Str::slug($categoryName) }}">
                            <span class="category-icon">
                                @php
                                    $icon = match(true) {
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

                @if(empty($catalog))
                    <div class="shop-empty-state">
                        Für diesen Shop sind aktuell noch keine Produkte verfügbar.
                    </div>
                @else
                    <div class="shop-category-stack">
                        @foreach($groupedCatalog as $categoryName => $products)
                            <section
                                class="shop-product-section"
                                id="category-{{ Str::slug($categoryName) }}"
                            >
                                <div class="shop-section-head">
                                    <h2>{{ $categoryName }}</h2>
                                    <a href="#category-{{ Str::slug($categoryName) }}">Alle anzeigen</a>
                                </div>

                                <div class="shop-product-row">
                                    @foreach($products as $product)
                                        @php
                                            $variant = $product['variants'][0] ?? null;

                                            $price = $variant['price']
                                                ?? $product['lowest_price']
                                                ?? null;

                                            $imageUrl = $product['image_url'] ?? null;
                                            $isAvailable = $product['is_available'] ?? false;
                                            $variantId = $variant['id'] ?? null;
                                        @endphp

                                        <article class="shop-product-card {{ ! $isAvailable ? 'is-disabled' : '' }}">
                                            <div class="shop-product-image">
                                                @if($imageUrl)
                                                    <img
                                                        src="{{ $imageUrl }}"
                                                        alt="{{ $product['name'] ?? 'Produktbild' }}"
                                                        loading="lazy"
                                                    >
                                                @else
                                                    <span>{{ mb_substr($product['name'] ?? 'P', 0, 1) }}</span>
                                                @endif
                                            </div>

                                            <div class="shop-product-body">
                                                <h3>{{ $product['name'] ?? 'Produkt' }}</h3>

                                                <div class="shop-product-bottom">
                                                    <strong>
                                                        @if($price !== null)
                                                            {{ number_format((float) $price, 2, ',', '.') }} €
                                                        @else
                                                            Preis folgt
                                                        @endif
                                                    </strong>

                                                    <button
                                                        type="button"
                                                        class="shop-product-plus add-to-cart"
                                                        @disabled(! $isAvailable || empty($variantId))
                                                        data-variant-id="{{ $variantId }}"
                                                        data-product-id="{{ $product['id'] ?? '' }}"
                                                        data-product-name="{{ $product['name'] ?? '' }}"
                                                        data-price="{{ $price ?? '' }}"
                                                        data-image-url="{{ $imageUrl ?? '' }}"
                                                    >
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
                    <div>
                        <span>Zwischensumme</span>
                        <strong id="cartSubtotal">0,00 €</strong>
                    </div>

                    <p>
                        Preise werden vor dem Checkout noch einmal serverseitig geprüft.
                    </p>

                    <button type="button" class="cart-checkout-button" id="cartCheckoutButton" disabled>
                        Weiter zur Kasse
                    </button>
                </div>
            </div>
        </aside>

        <div class="cart-backdrop" id="cartBackdrop"></div>
    </main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const shopPage = document.querySelector('.shop-page');

    if (!shopPage) {
        return;
    }

    const cartDrawer = document.querySelector('#cartDrawer');
    const cartBackdrop = document.querySelector('#cartBackdrop');
    const cartFloatingButton = document.querySelector('#cartFloatingButton');
    const cartCloseButton = document.querySelector('#cartCloseButton');
    const cartItems = document.querySelector('#cartItems');
    const cartSubtotal = document.querySelector('#cartSubtotal');
    const cartFloatingTotal = document.querySelector('#cartFloatingTotal');
    const cartFloatingCount = document.querySelector('#cartFloatingCount');
    const cartCheckoutButton = document.querySelector('#cartCheckoutButton');

    const formatter = new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    });

    const cart = new Map();

    const openCart = () => {
        cartDrawer.classList.add('is-open');
        cartDrawer.setAttribute('aria-hidden', 'false');
        cartBackdrop.classList.add('is-visible');
    };

    const closeCart = () => {
        cartDrawer.classList.remove('is-open');
        cartDrawer.setAttribute('aria-hidden', 'true');
        cartBackdrop.classList.remove('is-visible');
    };

    const getCartCount = () => {
        return [...cart.values()].reduce((sum, item) => sum + item.quantity, 0);
    };

    const getCartTotal = () => {
        return [...cart.values()].reduce((sum, item) => {
            return sum + (item.price * item.quantity);
        }, 0);
    };

    const renderCart = () => {
        const items = [...cart.values()];
        const count = getCartCount();
        const total = getCartTotal();

        cartSubtotal.textContent = formatter.format(total);
        cartFloatingTotal.textContent = formatter.format(total);
        cartFloatingCount.textContent = count;
        cartCheckoutButton.disabled = count === 0;

        if (count > 0) {
            cartFloatingButton.classList.add('is-visible');
        } else {
            cartFloatingButton.classList.remove('is-visible');
        }

        if (items.length === 0) {
            cartItems.innerHTML = `
                <div class="cart-empty">
                    Dein Warenkorb ist noch leer.
                </div>
            `;
            return;
        }

        cartItems.innerHTML = items.map((item) => {
            const image = item.imageUrl
                ? `<img src="${item.imageUrl}" alt="">`
                : `<span>${item.name.charAt(0)}</span>`;

            return `
                <div class="cart-item" data-variant-id="${item.variantId}">
                    <div class="cart-item-image">
                        ${image}
                    </div>

                    <div class="cart-item-main">
                        <h3>${item.name}</h3>
                        <p>${formatter.format(item.price)} · ${formatter.format(item.price * item.quantity)}</p>

                        <div class="cart-item-controls">
                            <button type="button" data-action="decrease" data-variant-id="${item.variantId}">−</button>
                            <span>${item.quantity}</span>
                            <button type="button" data-action="increase" data-variant-id="${item.variantId}">+</button>
                            <button type="button" class="cart-remove" data-action="remove" data-variant-id="${item.variantId}">×</button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    };

    document.querySelectorAll('.add-to-cart').forEach((button) => {
        button.addEventListener('click', () => {
            const variantId = button.dataset.variantId;

            if (!variantId) {
                return;
            }

            const existing = cart.get(variantId);

            if (existing) {
                existing.quantity += 1;
                cart.set(variantId, existing);
            } else {
                cart.set(variantId, {
                    variantId,
                    productId: button.dataset.productId,
                    name: button.dataset.productName || 'Produkt',
                    price: Number(button.dataset.price || 0),
                    imageUrl: button.dataset.imageUrl || '',
                    quantity: 1,
                });
            }

            renderCart();
            openCart();
        });
    });

    cartItems.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-action]');

        if (!button) {
            return;
        }

        const variantId = button.dataset.variantId;
        const action = button.dataset.action;
        const item = cart.get(variantId);

        if (!item) {
            return;
        }

        if (action === 'increase') {
            item.quantity += 1;
            cart.set(variantId, item);
        }

        if (action === 'decrease') {
            item.quantity -= 1;

            if (item.quantity <= 0) {
                cart.delete(variantId);
            } else {
                cart.set(variantId, item);
            }
        }

        if (action === 'remove') {
            cart.delete(variantId);
        }

        renderCart();
    });

    cartFloatingButton.addEventListener('click', openCart);
    cartCloseButton.addEventListener('click', closeCart);
    cartBackdrop.addEventListener('click', closeCart);

    cartCheckoutButton.addEventListener('click', () => {
        const payload = {
            shop_id: Number(shopPage.dataset.shopId),
            postcode: shopPage.dataset.postcode || null,
            source: 'kioskheld',
            items: [...cart.values()].map((item) => ({
                variant_id: Number(item.variantId),
                quantity: item.quantity,
            })),
        };

        console.log('Cart validate payload:', payload);

        alert('Nächster Schritt: Warenkorb serverseitig validieren.');
    });

    renderCart();
});
</script>


@endsection
