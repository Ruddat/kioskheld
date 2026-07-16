@extends('layouts.marketing')

@section('title', __('faq.meta_title'))

@section('content')
    <main class="faq-page">
        <section class="faq-hero">
            <div class="container faq-hero-grid">
                <div class="faq-hero-copy">
                    <p class="faq-kicker">{{ __('faq.hero.kicker') }}</p>

                    <h1>
                        {{ __('faq.hero.headline') }}
                        <span>{{ __('faq.hero.headline_accent') }}</span>
                    </h1>

                    <p>
                        {{ __('faq.hero.description') }}
                    </p>

                    <div class="faq-hero-actions" aria-label="FAQ Schnellzugriff">
                        <a href="#kunden" class="faq-btn faq-btn-primary">{{ __('faq.hero.btn_customers') }}</a>
                        <a href="#partner" class="faq-btn faq-btn-secondary">{{ __('faq.hero.btn_partners') }}</a>
                    </div>
                </div>

                <div class="faq-hero-panel" aria-label="Kioskheld FAQ Übersicht">
                    <div class="faq-panel-card faq-panel-card-main">
                        <span>01</span>
                        <strong>{{ __('faq.hero.panel_1_title') }}</strong>
                        <p>{{ __('faq.hero.panel_1_text') }}</p>
                    </div>

                    <div class="faq-panel-card">
                        <span>02</span>
                        <strong>{{ __('faq.hero.panel_2_title') }}</strong>
                        <p>{{ __('faq.hero.panel_2_text') }}</p>
                    </div>

                    <div class="faq-panel-card">
                        <span>03</span>
                        <strong>{{ __('faq.hero.panel_3_title') }}</strong>
                        <p>{{ __('faq.hero.panel_3_text') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="faq-section">
            <div class="container">
                <div class="faq-intro">
                    <p class="faq-section-kicker">{{ __('faq.intro.kicker') }}</p>

                    <h2>{{ __('faq.intro.title') }}</h2>

                    <p>
                        {{ __('faq.intro.description') }}
                    </p>
                </div>

                <div class="faq-layout">
                    <aside class="faq-sidebar" aria-label="FAQ Bereiche">
                        <a href="#kunden">
                            <span>{{ __('faq.sidebar.customers_label') }}</span>
                            <strong>{{ __('faq.sidebar.customers_title') }}</strong>
                        </a>

                        <a href="#partner">
                            <span>{{ __('faq.sidebar.partner_label') }}</span>
                            <strong>{{ __('faq.sidebar.partner_title') }}</strong>
                        </a>

                        <a href="{{ route('partner.register') }}">
                            <span>{{ __('faq.sidebar.join_label') }}</span>
                            <strong>{{ __('faq.sidebar.join_title') }}</strong>
                        </a>
                    </aside>

                    <div class="faq-content">
                        <section class="faq-group" id="kunden">
                            <div class="faq-group-head">
                                <p>{{ __('faq.customers.group_label') }}</p>
                                <h2>{{ __('faq.customers.group_title') }}</h2>
                            </div>

                            <div class="faq-list">
                                <details class="faq-item" open>
                                    <summary>{{ __('faq.customers.q1') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.customers.a1') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.customers.q2') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.customers.a2') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.customers.q3') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.customers.a3') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.customers.q4') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.customers.a4') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.customers.q5') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.customers.a5') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.customers.q6') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.customers.a6') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.customers.q7') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.customers.a7') }}</p>
                                    </div>
                                </details>
                            </div>
                        </section>

                        <section class="faq-group" id="partner">
                            <div class="faq-group-head">
                                <p>{{ __('faq.partners.group_label') }}</p>
                                <h2>{{ __('faq.partners.group_title') }}</h2>
                            </div>

                            <div class="faq-list">
                                <details class="faq-item" open>
                                    <summary>{{ __('faq.partners.q1') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.partners.a1') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.partners.q2') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.partners.a2') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.partners.q3') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.partners.a3') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.partners.q4') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.partners.a4') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.partners.q5') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.partners.a5') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.partners.q6') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.partners.a6') }}</p>
                                    </div>
                                </details>

                                <details class="faq-item">
                                    <summary>{{ __('faq.partners.q7') }}</summary>

                                    <div class="faq-answer">
                                        <p>{{ __('faq.partners.a7') }}</p>
                                    </div>
                                </details>
                            </div>
                        </section>
                    </div>
                </div>

                <section class="faq-cta">
                    <div>
                        <p>{{ __('faq.cta.label') }}</p>
                        <h2>{{ __('faq.cta.title') }}</h2>
                        <span>
                            {{ __('faq.cta.description') }}
                        </span>
                    </div>

                    <a href="{{ route('partner.register') }}">{{ __('faq.cta.button') }}</a>
                </section>
            </div>
        </section>
    </main>

    <x-marketing.footer />
@endsection