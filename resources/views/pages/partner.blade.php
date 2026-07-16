@extends('layouts.marketing')

@section('title', __('partner.landing.meta_title'))

@section('content')
    <main class="partner-page">
        <div class="shop-app-nav-wrap">
            <x-marketing.nav />
        </div>

        <section class="partner-hero">
            <div class="container partner-hero-grid">
                <div class="partner-hero-content">
                    <p class="eyebrow">{{ __('partner.landing.hero.eyebrow') }}</p>

                    <h1>
                        {{ __('partner.landing.hero.headline') }}
                        <span>{{ __('partner.landing.hero.headline_accent') }}</span>
                        <strong>{{ __('partner.landing.hero.headline_strong') }}</strong>
                    </h1>

                    <p class="lead">
                        {{ __('partner.landing.hero.lead') }}
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('partner.register') }}" class="btn btn-primary">
                            {{ __('partner.landing.hero.btn_primary') }}
                        </a>

                        <a href="#how-it-works" class="btn btn-secondary">
                            {{ __('partner.landing.hero.btn_secondary') }}
                        </a>
                    </div>

                    <div class="partner-hero-facts" aria-label="Kioskheld Partner Vorteile">
                        <div>
                            <span>⚡</span>
                            <strong>{{ __('partner.landing.hero.fact_1_title') }}</strong>
                            <small>{{ __('partner.landing.hero.fact_1_text') }}</small>
                        </div>

                        <div>
                            <span>🛒</span>
                            <strong>{{ __('partner.landing.hero.fact_2_title') }}</strong>
                            <small>{{ __('partner.landing.hero.fact_2_text') }}</small>
                        </div>

                        <div>
                            <span>📍</span>
                            <strong>{{ __('partner.landing.hero.fact_3_title') }}</strong>
                            <small>{{ __('partner.landing.hero.fact_3_text') }}</small>
                        </div>
                    </div>
                </div>

                <aside class="partner-hero-panel" aria-label="Partner Startpaket">
                    <div class="partner-bag-card">
                        <div class="partner-bag-top">
                            <span class="mini-label">{{ __('partner.landing.hero.panel_label') }}</span>
                            <span class="badge">{{ __('partner.landing.hero.panel_badge') }}</span>
                        </div>

                        <h2>{{ __('partner.landing.hero.panel_title') }}</h2>

                        <ul>
                            <li>
                                <span>✓</span>
                                {{ __('partner.landing.hero.panel_item_1') }}
                            </li>
                            <li>
                                <span>✓</span>
                                {{ __('partner.landing.hero.panel_item_2') }}
                            </li>
                            <li>
                                <span>✓</span>
                                {{ __('partner.landing.hero.panel_item_3') }}
                            </li>
                            <li>
                                <span>✓</span>
                                {{ __('partner.landing.hero.panel_item_4') }}
                            </li>
                            <li>
                                <span>✓</span>
                                {{ __('partner.landing.hero.panel_item_5') }}
                            </li>
                        </ul>

                        <a href="{{ route('partner.register') }}" class="partner-panel-link">
                            {{ __('partner.landing.hero.panel_link') }}
                        </a>
                    </div>
                </aside>
            </div>
        </section>

        <section class="partner-category-strip" aria-label="Typische Kioskheld Kategorien">
            <div class="container">
                <div class="partner-category-card">
                    <div>
                        <span>🥤</span>
                        <strong>{{ __('partner.landing.categories.cat_1') }}</strong>
                    </div>

                    <div>
                        <span>⚡</span>
                        <strong>{{ __('partner.landing.categories.cat_2') }}</strong>
                    </div>

                    <div>
                        <span>🍟</span>
                        <strong>{{ __('partner.landing.categories.cat_3') }}</strong>
                    </div>

                    <div>
                        <span>🍬</span>
                        <strong>{{ __('partner.landing.categories.cat_4') }}</strong>
                    </div>

                    <div>
                        <span>🍦</span>
                        <strong>{{ __('partner.landing.categories.cat_5') }}</strong>
                    </div>

                    <div>
                        <span>🎁</span>
                        <strong>{{ __('partner.landing.categories.cat_6') }}</strong>
                    </div>

                    <div>
                        <span>％</span>
                        <strong>{{ __('partner.landing.categories.cat_7') }}</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="partner-section" id="how-it-works">
            <div class="container">
                <div class="section-heading">
                    <p class="eyebrow">{{ __('partner.landing.how.eyebrow') }}</p>
                    <h2>{{ __('partner.landing.how.title') }}</h2>
                    <p>
                        {{ __('partner.landing.how.description') }}
                    </p>
                </div>

                <div class="steps-grid">
                    <article>
                        <span>1</span>
                        <h3>{{ __('partner.landing.how.step_1_title') }}</h3>
                        <p>
                            {{ __('partner.landing.how.step_1_text') }}
                        </p>
                    </article>

                    <article>
                        <span>2</span>
                        <h3>{{ __('partner.landing.how.step_2_title') }}</h3>
                        <p>
                            {{ __('partner.landing.how.step_2_text') }}
                        </p>
                    </article>

                    <article>
                        <span>3</span>
                        <h3>{{ __('partner.landing.how.step_3_title') }}</h3>
                        <p>
                            {{ __('partner.landing.how.step_3_text') }}
                        </p>
                    </article>

                    <article>
                        <span>4</span>
                        <h3>{{ __('partner.landing.how.step_4_title') }}</h3>
                        <p>
                            {{ __('partner.landing.how.step_4_text') }}
                        </p>
                    </article>
                </div>
            </div>
        </section>

<section class="partner-benefits">
    <div class="container partner-benefits-grid">
        <div class="benefit-copy">
            <p class="eyebrow">{{ __('partner.landing.benefits.eyebrow') }}</p>

            <h2>
                {{ __('partner.landing.benefits.headline') }}
                <span>{{ __('partner.landing.benefits.headline_accent') }}</span>
            </h2>

            <p>
                {{ __('partner.landing.benefits.description') }}
            </p>

            <div class="benefit-metrics" aria-label="Kioskheld Vorteile">
                <div>
                    <strong>{{ __('partner.landing.benefits.metric_1_value') }}</strong>
                    <span>{{ __('partner.landing.benefits.metric_1_label') }}</span>
                </div>

                <div>
                    <strong>{{ __('partner.landing.benefits.metric_2_value') }}</strong>
                    <span>{{ __('partner.landing.benefits.metric_2_label') }}</span>
                </div>

                <div>
                    <strong>{{ __('partner.landing.benefits.metric_3_value') }}</strong>
                    <span>{{ __('partner.landing.benefits.metric_3_label') }}</span>
                </div>
            </div>
        </div>

        <div class="benefit-list">
            <article>
                <span class="benefit-icon">⚡</span>
                <div>
                    <strong>{{ __('partner.landing.benefits.item_1_title') }}</strong>
                    <p>
                        {{ __('partner.landing.benefits.item_1_text') }}
                    </p>
                </div>
            </article>

            <article>
                <span class="benefit-icon">🛒</span>
                <div>
                    <strong>{{ __('partner.landing.benefits.item_2_title') }}</strong>
                    <p>
                        {{ __('partner.landing.benefits.item_2_text') }}
                    </p>
                </div>
            </article>

            <article>
                <span class="benefit-icon">📍</span>
                <div>
                    <strong>{{ __('partner.landing.benefits.item_3_title') }}</strong>
                    <p>
                        {{ __('partner.landing.benefits.item_3_text') }}
                    </p>
                </div>
            </article>

            <article>
                <span class="benefit-icon">✅</span>
                <div>
                    <strong>{{ __('partner.landing.benefits.item_4_title') }}</strong>
                    <p>
                        {{ __('partner.landing.benefits.item_4_text') }}
                    </p>
                </div>
            </article>
        </div>
    </div>
</section>

        <section class="partner-final-cta">
            <div class="container">
                <div class="partner-final-card">
                    <div>
                        <p class="eyebrow">{{ __('partner.landing.cta.eyebrow') }}</p>
                        <h2>{{ __('partner.landing.cta.title') }}</h2>
                        <p>
                            {{ __('partner.landing.cta.description') }}
                        </p>
                    </div>

                    <a href="{{ route('partner.register') }}" class="btn btn-primary">
                        {{ __('partner.landing.cta.button') }}
                    </a>
                </div>
            </div>
        </section>

        <x-marketing.footer />
    </main>
@endsection