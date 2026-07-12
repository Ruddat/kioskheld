@extends('layouts.marketing')

@section('title', __('home.meta.title'))

@section('meta_description', __('home.meta.description'))

@section('content')
    <header class="hero">
        <x-marketing.nav />

        <div class="container hero-grid" id="find">
            <div class="hero-content">
                <h1 class="headline">
                    {{ __('home.hero.headline') }}
                    <span>{{ __('home.hero.headline_accent') }}</span>
                </h1>

                <p class="subline">
                    {{ __('home.hero.subline') }}
                </p>

                <form class="postcode-form" id="postcodeForm" data-postcode-check-url="{{ route('postcode.check') }}"
                    data-shop-selection-url="{{ route('shops.selection') }}"
                    data-shop-legacy-url="{{ route('shops.legacy', ['shopSlug' => '__SHOP_SLUG__']) }}" novalidate>
                    <label class="postcode-field" for="postcode">
                        <span aria-hidden="true">⌕</span>

                        <input id="postcode" name="postcode" inputmode="numeric" autocomplete="postal-code"
                            placeholder="{{ __('home.hero.postcode_placeholder') }}" maxlength="5" pattern="[0-9]{5}"
                            required>
                    </label>

                    <button class="submit-btn" type="submit">
                        {{ __('home.hero.submit') }}
                    </button>
                </form>

                <div class="hint">
                    ⌖ {{ __('home.hero.postcode_example') }}
                </div>

                <div class="trust-row">
                    <div class="trust-item">
                        <div class="trust-icon">⏱</div>

                        <div class="trust-copy">
                            <strong>{{ __('home.trust.delivery_title') }}</strong>
                            <span>{{ __('home.trust.delivery_value') }}</span>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon">🧺</div>

                        <div class="trust-copy">
                            <strong>{{ __('home.trust.minimum_title') }}</strong>
                            <span>{{ __('home.trust.minimum_value') }}</span>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon">✓</div>

                        <div class="trust-copy">
                            <strong>{{ __('home.trust.payment_title') }}</strong>
                            <span>{{ __('home.trust.payment_value') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container category-panel">
            <div class="category-card" aria-label="{{ __('home.categories.aria_label') }}">
                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/getraenke.png') }}" alt="">
                    </span>

                    <span>{{ __('home.categories.drinks') }}</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/energy.png') }}" alt="">
                    </span>

                    <span>{{ __('home.categories.energy') }}</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/chips.png') }}" alt="">
                    </span>

                    <span>{{ __('home.categories.snacks') }}</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/suesses.png') }}" alt="">
                    </span>

                    <span>{{ __('home.categories.sweets') }}</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/eis.png') }}" alt="">
                    </span>

                    <span>{{ __('home.categories.ice_cream') }}</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/bundles.png') }}" alt="">
                    </span>

                    <span>{{ __('home.categories.bundles') }}</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/angebote.png') }}" alt="">
                    </span>

                    <span>{{ __('home.categories.offers') }}</span>
                </a>
            </div>
        </div>

        <section class="how-section" id="how">
            <div class="container">
                <h2 class="how-title">
                    {{ __('home.how.title') }}
                </h2>

                <div class="how-steps">
                    <article class="how-step">
                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/plz.png') }}" alt="">
                        </div>

                        <div class="how-label">
                            <span>1</span>
                            <strong>{{ __('home.how.postcode_title') }}</strong>
                        </div>

                        <p>{{ __('home.how.postcode_text') }}</p>
                    </article>

                    <div class="how-arrow" aria-hidden="true">→</div>

                    <article class="how-step">
                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/kiosk.png') }}" alt="">
                        </div>

                        <div class="how-label">
                            <span>2</span>
                            <strong>{{ __('home.how.shop_title') }}</strong>
                        </div>

                        <p>{{ __('home.how.shop_text') }}</p>
                    </article>

                    <div class="how-arrow" aria-hidden="true">→</div>

                    <article class="how-step">
                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/cart.png') }}" alt="">
                        </div>

                        <div class="how-label">
                            <span>3</span>
                            <strong>{{ __('home.how.order_title') }}</strong>
                        </div>

                        <p>{{ __('home.how.order_text') }}</p>
                    </article>

                    <div class="how-arrow" aria-hidden="true">→</div>

                    <article class="how-step">
                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/delivery.png') }}" alt="">
                        </div>

                        <div class="how-label">
                            <span>4</span>
                            <strong>{{ __('home.how.delivery_title') }}</strong>
                        </div>

                        <p>{{ __('home.how.delivery_text') }}</p>
                    </article>
                </div>
            </div>
        </section>

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

                        <a class="partner-banner__button" href="{{ route('partner.index') }}">
                            {{ __('home.partner.button') }}
                        </a>
                    </div>

                    <div class="partner-banner__badge" aria-hidden="true">
                        <span class="partner-banner__crown">♛</span>
                        <strong>KIOSK<br>HELD</strong>
                    </div>
                </div>
            </div>
        </section>

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
