@extends('layouts.marketing')

@section('title', __('about.meta_title'))

@section('content')
    <main class="about-page">
        <section class="about-hero">
            <div class="container about-hero-grid">
                <div class="about-hero-copy">
                    <p class="about-kicker">{{ __('about.hero.kicker') }}</p>

                    <h1>
                        {{ __('about.hero.headline') }}
                        <span>{{ __('about.hero.headline_accent') }}</span>
                    </h1>

                    <p>
                        {{ __('about.hero.description') }}
                    </p>

                    <div class="about-hero-actions">
                        <a href="{{ route('home') }}#find" class="about-btn about-btn-primary">
                            {{ __('about.hero.btn_find') }}
                        </a>

                        <a href="{{ route('partner.index') }}" class="about-btn about-btn-outline">
                            {{ __('about.hero.btn_partner') }}
                        </a>
                    </div>
                </div>

                <div class="about-hero-card">
                    <div class="about-hero-badge">{{ __('about.hero.card_badge') }}</div>

                    <h2>{{ __('about.hero.card_title') }}</h2>

                    <p>
                        {{ __('about.hero.card_description') }}
                    </p>

                    <div class="about-hero-steps">
                        <div>
                            <strong>01</strong>
                            <span>{{ __('about.hero.step_1') }}</span>
                        </div>

                        <div>
                            <strong>02</strong>
                            <span>{{ __('about.hero.step_2') }}</span>
                        </div>

                        <div>
                            <strong>03</strong>
                            <span>{{ __('about.hero.step_3') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section about-pullup-section">
            <div class="container">
                <div class="about-statement">
                    <p class="about-kicker about-kicker-dark">{{ __('about.why.kicker') }}</p>

                    <h2>
                        {{ __('about.why.headline') }}
                    </h2>

                    <p>
                        {{ __('about.why.description') }}
                    </p>
                </div>

                <div class="about-proof-grid">
                    <article class="about-proof-card">
                        <span>{{ __('about.why.customers_label') }}</span>
                        <strong>{{ __('about.why.customers_title') }}</strong>
                        <p>
                            {{ __('about.why.customers_text') }}
                        </p>
                    </article>

                    <article class="about-proof-card">
                        <span>{{ __('about.why.merchants_label') }}</span>
                        <strong>{{ __('about.why.merchants_title') }}</strong>
                        <p>
                            {{ __('about.why.merchants_text') }}
                        </p>
                    </article>

                    <article class="about-proof-card">
                        <span>{{ __('about.why.region_label') }}</span>
                        <strong>{{ __('about.why.region_title') }}</strong>
                        <p>
                            {{ __('about.why.region_text') }}
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="about-section about-dark-section">
            <div class="container about-split">
                <div class="about-split-copy">
                    <p class="about-kicker">{{ __('about.stance.kicker') }}</p>

                    <h2>
                        {{ __('about.stance.headline_1') }}
                        {{ __('about.stance.headline_2') }}
                    </h2>

                    <p>
                        {{ __('about.stance.description') }}
                    </p>
                </div>

                <div class="about-dark-card">
                    <div class="about-dark-card-line">
                        <span>{{ __('about.stance.point_1_label') }}</span>
                        <strong>{{ __('about.stance.point_1_text') }}</strong>
                    </div>

                    <div class="about-dark-card-line">
                        <span>{{ __('about.stance.point_2_label') }}</span>
                        <strong>{{ __('about.stance.point_2_text') }}</strong>
                    </div>

                    <div class="about-dark-card-line">
                        <span>{{ __('about.stance.point_3_label') }}</span>
                        <strong>{{ __('about.stance.point_3_text') }}</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="container">
                <div class="about-section-head">
                    <p class="about-kicker about-kicker-dark">{{ __('about.features.kicker') }}</p>

                    <h2>{{ __('about.features.headline') }}</h2>
                </div>

                <div class="about-feature-grid">
                    <article class="about-feature-card">
                        <div class="about-feature-icon">⌖</div>
                        <h3>{{ __('about.features.local_title') }}</h3>
                        <p>
                            {{ __('about.features.local_text') }}
                        </p>
                    </article>

                    <article class="about-feature-card">
                        <div class="about-feature-icon">↯</div>
                        <h3>{{ __('about.features.fast_title') }}</h3>
                        <p>
                            {{ __('about.features.fast_text') }}
                        </p>
                    </article>

                    <article class="about-feature-card">
                        <div class="about-feature-icon">▣</div>
                        <h3>{{ __('about.features.clean_title') }}</h3>
                        <p>
                            {{ __('about.features.clean_text') }}
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="about-section about-engine-section">
            <div class="container about-engine">
                <div>
                    <p class="about-kicker">{{ __('about.engine.kicker') }}</p>

                    <h2>{{ __('about.engine.headline') }}</h2>
                </div>

                <div class="about-engine-card">
                    <p>
                        {{ __('about.engine.description_1') }}
                    </p>

                    <p>
                        {{ __('about.engine.description_2') }}
                    </p>
                </div>
            </div>
        </section>

        <section class="about-section about-final-section">
            <div class="container">
                <div class="about-final-cta">
                    <div>
                        <p class="about-kicker about-kicker-dark">{{ __('about.final.kicker') }}</p>

                        <h2>
                            {{ __('about.final.headline_1') }}
                            {{ __('about.final.headline_2') }}
                        </h2>

                        <p>
                            {{ __('about.final.description') }}
                        </p>
                    </div>

                    <div class="about-final-actions">
                        <a href="{{ route('home') }}#find" class="about-btn about-btn-primary">
                            {{ __('about.final.btn_find') }}
                        </a>

                        <a href="{{ route('partner.register') }}" class="about-btn about-btn-dark">
                            {{ __('about.final.btn_partner') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-marketing.footer />
@endsection