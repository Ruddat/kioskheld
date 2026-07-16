@extends('layouts.marketing')

@section('title', __('home.meta.title'))

@section('meta_description', __('home.meta.description'))

@section('content')
    {{-- ═══════════════════════════════════════════
         HERO
         ═══════════════════════════════════════════ --}}
    <header class="hero">
        <x-marketing.nav />

        <div class="container hero-grid" id="find">
            <div class="hero-content">
                <p class="hero-kicker">
                    {{ __('home.hero.kicker') }}
                </p>

                <h1 class="headline">
                    {{ __('home.hero.headline') }}
                    <span>{{ __('home.hero.headline_accent') }}</span>
                </h1>

                <p class="subline">
                    {{ __('home.hero.subline') }}
                </p>

                {{-- PLZ-Suchformular:
                 IDs, Routen und Data-Attribute nicht verändern. --}}
                <form class="postcode-form" id="postcodeForm" data-postcode-check-url="{{ route('postcode.check') }}"
                    data-shop-selection-url="{{ route('shops.selection') }}"
                    data-shop-legacy-url="{{ route('shops.legacy', ['shopSlug' => '__SHOP_SLUG__']) }}" novalidate>
                    <label class="postcode-field" for="postcode">
                        <span class="postcode-field__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M21 21L16.65 16.65M19 11A8 8 0 1 1 3 11A8 8 0 0 1 19 11Z" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>

                        <input id="postcode" name="postcode" inputmode="numeric" autocomplete="postal-code"
                            placeholder="{{ __('home.hero.postcode_placeholder') }}" maxlength="5" pattern="[0-9]{5}"
                            required>
                    </label>

                    <button class="submit-btn" type="submit">
                        {{ __('home.hero.submit') }}
                        <span aria-hidden="true">→</span>
                    </button>
                </form>

                <div class="hint">
                    <span aria-hidden="true">⌖</span>
                    {{ __('home.hero.postcode_example') }}
                </div>

                <div class="trust-row">
                    <div class="trust-item">
                        <div class="trust-icon" aria-hidden="true">⏱</div>

                        <div class="trust-copy">
                            <strong>{{ __('home.trust.delivery_title') }}</strong>
                            <span>{{ __('home.trust.delivery_value') }}</span>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon" aria-hidden="true">🧺</div>

                        <div class="trust-copy">
                            <strong>{{ __('home.trust.minimum_title') }}</strong>
                            <span>{{ __('home.trust.minimum_value') }}</span>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon" aria-hidden="true">✓</div>

                        <div class="trust-copy">
                            <strong>{{ __('home.trust.payment_title') }}</strong>
                            <span>{{ __('home.trust.payment_value') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-visual" aria-hidden="true">
                <div class="hero-visual__glow"></div>

                <img class="hero-visual__image" src="{{ asset('images/marketing/home/kioskheld-hero.webp') }}"
                    alt="" width="1400" height="1050" decoding="async" fetchpriority="high">

                <div class="hero-visual__fallback">
                    <div class="hero-visual__fallback-pin">♛</div>

                    <div class="hero-visual__fallback-shop">
                        <span>KIOSK</span>
                    </div>
                </div>

                <div class="hero-floating-card hero-floating-card--delivery">
                    <span class="hero-floating-card__icon">⏱</span>

                    <span>
                        <strong>{{ __('home.trust.delivery_title') }}</strong>
                        {{ __('home.trust.delivery_value') }}
                    </span>
                </div>

                <div class="hero-floating-card hero-floating-card--local">
                    <span class="hero-floating-card__icon">⌖</span>

                    <span>
                        <strong>{{ __('home.hero.local_title') }}</strong>
                        {{ __('home.hero.local_text') }}
                    </span>
                </div>
            </div>
        </div>
    </header>

    <main>
        {{-- ═══════════════════════════════════════════
             NEARBY KIOSKS (neu – dynamisch via JS)
             ═══════════════════════════════════════════ --}}
        <section class="nearby-kiosks nearby-kiosks--hidden" id="nearby-kiosks" aria-hidden="true">
            <div class="container">
                <div class="nearby-kiosks-heading">
                    <p class="nearby-kiosks-kicker">
                        {{ __('home.nearby.kicker') }}
                    </p>

                    <h2>
                        {{ __('home.nearby.title') }}
                    </h2>

                    <p class="nearby-kiosks-description">
                        {{ __('home.nearby.description') }}
                    </p>
                </div>

                <div class="nearby-kiosks-grid" id="nearbyKiosksGrid"></div>
            </div>
        </section>

        {{-- ═══════════════════════════════════════════
             HOW IT WORKS
             ═══════════════════════════════════════════ --}}
        <section class="how-section" id="how">
            <div class="container">
                <h2 class="how-title">
                    {{ __('home.how.title') }}
                </h2>

                <div class="how-timeline">
                    <div class="how-timeline__line" aria-hidden="true"></div>

                    <article class="how-step">
                        <div class="how-step__dot">
                            <span>1</span>
                        </div>

                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/plz.png') }}" alt="">
                        </div>

                        <div class="how-step__content">
                            <strong>{{ __('home.how.postcode_title') }}</strong>
                            <p>{{ __('home.how.postcode_text') }}</p>
                        </div>
                    </article>

                    <article class="how-step">
                        <div class="how-step__dot">
                            <span>2</span>
                        </div>

                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/kiosk.png') }}" alt="">
                        </div>

                        <div class="how-step__content">
                            <strong>{{ __('home.how.shop_title') }}</strong>
                            <p>{{ __('home.how.shop_text') }}</p>
                        </div>
                    </article>

                    <article class="how-step">
                        <div class="how-step__dot">
                            <span>3</span>
                        </div>

                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/cart.png') }}" alt="">
                        </div>

                        <div class="how-step__content">
                            <strong>{{ __('home.how.order_title') }}</strong>
                            <p>{{ __('home.how.order_text') }}</p>
                        </div>
                    </article>

                    <article class="how-step">
                        <div class="how-step__dot">
                            <span>4</span>
                        </div>

                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/delivery.png') }}" alt="">
                        </div>

                        <div class="how-step__content">
                            <strong>{{ __('home.how.delivery_title') }}</strong>
                            <p>{{ __('home.how.delivery_text') }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        {{-- ═══════════════════════════════════════════
             PARTNER CTA
             ═══════════════════════════════════════════ --}}
<section class="partner-cta" id="partner">
    <div class="container">
        <div class="partner-banner">
            <div class="partner-banner__content">
                <h2>
                    {{ __('home.partner.headline_before') }}
                    <span>{{ __('home.partner.kiosk') }}</span>

                    {{ __('home.partner.or') }}

                    <br>

                    <span>{{ __('home.partner.beverage_service') }}</span>
                </h2>

                <p>
                    {{ __('home.partner.description') }}
                </p>

                <a
                    class="partner-banner__button"
                    href="{{ route('partner.index') }}"
                >
                    {{ __('home.partner.button') }}
                </a>
            </div>

            <div class="partner-banner__visual" aria-hidden="true">
                <img
                    class="partner-banner__image"
                    src="{{ asset('images/marketing/home/partner-cta.webp') }}"
                    alt=""
                    width="1400"
                    height="933"
                    loading="lazy"
                    decoding="async"
                >
            </div>
        </div>
    </div>
</section>

        {{-- ═══════════════════════════════════════════
             CATALOG / CATEGORIES
             ═══════════════════════════════════════════ --}}
        <section class="home-catalog" id="sortiment">
            <div class="container">
                <div class="home-catalog-heading">
                    <div class="home-catalog-heading__copy">
                        <p class="home-catalog-kicker">
                            {{ __('home.catalog.kicker') }}
                        </p>

                        <h2>
                            {{ __('home.catalog.title') }}
                            <span>{{ __('home.catalog.title_accent') }}</span>
                        </h2>
                    </div>

                    <p class="home-catalog-heading__text">
                        {{ __('home.catalog.description') }}
                    </p>
                </div>

                @if ($catalogCategories->isNotEmpty())
                    <div class="home-catalog-grid">
                        @foreach ($catalogCategories as $category)
                            <article class="home-catalog-card">
                                <a class="home-catalog-card__link"
                                    href="{{ route('catalog.categories.show', [
                                        'locale' => app()->getLocale(),
                                        'categorySlug' => $category->slug,
                                    ]) }}">
                                    <div class="home-catalog-card__media">
                                        @if (filled($category->image_url))
                                            <img class="home-catalog-card__image" src="{{ $category->image_url }}"
                                                alt="" loading="lazy" decoding="async"
                                                onerror="
                                                this.hidden = true;
                                                this.nextElementSibling.hidden = false;
                                            ">

                                            <span class="home-catalog-card__placeholder" hidden aria-hidden="true">
                                                {{ mb_strtoupper(mb_substr($category->name, 0, 1)) }}
                                            </span>
                                        @else
                                            <span class="home-catalog-card__placeholder" aria-hidden="true">
                                                {{ mb_strtoupper(mb_substr($category->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="home-catalog-card__body">
                                        <p class="home-catalog-card__label">
                                            {{ __('home.catalog.category_label') }}
                                        </p>

                                        <h3>{{ $category->name }}</h3>

                                        @if ($category->description)
                                            <p class="home-catalog-card__description">
                                                {{ \Illuminate\Support\Str::limit($category->description, 90) }}
                                            </p>
                                        @endif

                                        <div class="home-catalog-card__footer">
                                            <span>
                                                {{ $category->active_products_count }}

                                                {{ $category->active_products_count === 1 ? __('home.catalog.product') : __('home.catalog.products') }}
                                            </span>

                                            <strong aria-hidden="true">→</strong>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="home-catalog-empty">
                        <strong>{{ __('home.catalog.empty_title') }}</strong>

                        <p>{{ __('home.catalog.empty_text') }}</p>
                    </div>
                @endif

                <div class="home-catalog-actions">
                    <a class="home-catalog-button"
                        href="{{ route('catalog.categories.index', [
                            'locale' => app()->getLocale(),
                        ]) }}">
                        {{ __('home.catalog.show_categories') }}
                    </a>

                    <a class="home-catalog-button home-catalog-button--secondary"
                        href="{{ route('catalog.products.index', [
                            'locale' => app()->getLocale(),
                        ]) }}">
                        {{ __('home.catalog.show_products') }}
                    </a>
                </div>

                <p class="home-catalog-notice">
                    {{ __('home.catalog.availability_notice') }}
                </p>
            </div>
        </section>

        {{-- ═══════════════════════════════════════════
             BENEFITS
             ═══════════════════════════════════════════ --}}
        <section class="benefits" id="about">
            <div class="container benefit-grid">
                <div class="benefit">
                    <span class="i">☆</span>

                    <div>
                        <strong>{{ __('home.benefits.products_title') }}</strong>
                        <span>{{ __('home.benefits.products_text') }}</span>
                    </div>
                </div>

                <div class="benefit">
                    <span class="i">♡</span>

                    <div>
                        <strong>{{ __('home.benefits.local_title') }}</strong>
                        <span>{{ __('home.benefits.local_text') }}</span>
                    </div>
                </div>

                <div class="benefit">
                    <span class="i">☏</span>

                    <div>
                        <strong>{{ __('home.benefits.service_title') }}</strong>
                        <span>{{ __('home.benefits.service_text') }}</span>
                    </div>
                </div>

                <div class="benefit">
                    <span class="i">▣</span>

                    <div>
                        <strong>{{ __('home.benefits.payment_title') }}</strong>
                        <span>{{ __('home.benefits.payment_text') }}</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-marketing.footer />

    <div class="toast" id="toast" role="status" aria-live="polite"></div>
@endsection
