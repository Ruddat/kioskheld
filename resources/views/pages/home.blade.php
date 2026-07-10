@extends('layouts.marketing')

@section('title', 'Kioskheld – Dein Kiosk. Geliefert in Minuten.')

@section('meta_description',
    'Kioskheld liefert Snacks, Getränke, Süßes und Bundles schnell zu dir. Einfach PLZ eingeben
    und Kioskheld in deiner Nähe finden.')

@section('content')
    <header class="hero">
        <nav class="container nav" aria-label="Hauptnavigation">
            <x-marketing.logo />

            <div class="nav-links">
                <a href="#how">So funktioniert's</a>
                <a href="#find">Kioske finden</a>
                <a href="#bundles">Sortiment</a>
                <a href="#partner">Für Partner</a>
                <a href="#about">Über uns</a>
                <a class="partner-btn" href="#partner">👤 Partner werden</a>
            </div>

            <div class="mobile-menu" aria-hidden="true">☰</div>
        </nav>

        <div class="container hero-grid" id="find">
            <div class="hero-content">
                <h1 class="headline">
                    Dein Kiosk.
                    <span>Geliefert in Minuten.</span>
                </h1>

                <p class="subline">
                    Snacks, Getränke, Süßes & mehr – schnell, einfach und zuverlässig zu dir.
                </p>

                <form class="postcode-form" id="postcodeForm" novalidate>
                    <label class="postcode-field" for="postcode">
                        <span aria-hidden="true">⌕</span>

                        <input id="postcode" name="postcode" inputmode="numeric" autocomplete="postal-code"
                            placeholder="Deine Postleitzahl eingeben" maxlength="5" pattern="[0-9]{5}" required>
                    </label>

                    <button class="submit-btn" type="submit">
                        Kioskheld finden
                    </button>
                </form>

                <div class="hint">
                    ⌖ z. B. 31224, 38100, 38102
                </div>

                <div class="trust-row">
                    <div class="trust-item">
                        <div class="trust-icon">⏱</div>
                        <div class="trust-copy">
                            <strong>Schnelle Lieferung</strong>
                            <span>25–40 Minuten</span>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon">🧺</div>
                        <div class="trust-copy">
                            <strong>Mindestbestellwert</strong>
                            <span>ab 15,00 €</span>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon">✓</div>
                        <div class="trust-copy">
                            <strong>Sicher & bequem</strong>
                            <span>Online bezahlen</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container category-panel">
            <div class="category-card" aria-label="Sortiment Kategorien">
                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/getraenke.png') }}" alt="">
                    </span>
                    <span>Getränke</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/energy.png') }}" alt="">
                    </span>
                    <span>Energy</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/chips.png') }}" alt="">
                    </span>
                    <span>Chips & Snacks</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/suesses.png') }}" alt="">
                    </span>
                    <span>Süßes</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/eis.png') }}" alt="">
                    </span>
                    <span>Eis</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/bundles.png') }}" alt="">
                    </span>
                    <span>Bundles</span>
                </a>

                <a class="category" href="#bundles">
                    <span class="cat-icon">
                        <img src="{{ asset('images/marketing/categories/angebote.png') }}" alt="">
                    </span>
                    <span>Angebote</span>
                </a>
            </div>
        </div>

        <section class="how-section" id="how">
            <div class="container">
                <h2 class="how-title">So funktioniert Kioskheld</h2>

                <div class="how-steps">
                    <article class="how-step">
                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/plz.png') }}" alt="">
                        </div>

                        <div class="how-label">
                            <span>1</span>
                            <strong>PLZ eingeben</strong>
                        </div>

                        <p>
                            Gib deine Postleitzahl ein und finde Kioskhelden in deiner Nähe.
                        </p>
                    </article>

                    <div class="how-arrow" aria-hidden="true">→</div>

                    <article class="how-step">
                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/kiosk.png') }}" alt="">
                        </div>

                        <div class="how-label">
                            <span>2</span>
                            <strong>Kiosk auswählen</strong>
                        </div>

                        <p>
                            Wähle deinen Kioskheld und sieh Lieferzeit & Mindestbestellwert.
                        </p>
                    </article>

                    <div class="how-arrow" aria-hidden="true">→</div>

                    <article class="how-step">
                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/cart.png') }}" alt="">
                        </div>

                        <div class="how-label">
                            <span>3</span>
                            <strong>Bestellen</strong>
                        </div>

                        <p>
                            Stöbere im Sortiment und lege deine Lieblingsprodukte in den Warenkorb.
                        </p>
                    </article>

                    <div class="how-arrow" aria-hidden="true">→</div>

                    <article class="how-step">
                        <div class="how-icon">
                            <img src="{{ asset('images/marketing/how/delivery.png') }}" alt="">
                        </div>

                        <div class="how-label">
                            <span>4</span>
                            <strong>Liefern lassen</strong>
                        </div>

                        <p>
                            Lehne dich zurück und lass dir Snacks & Getränke liefern.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="bundles" id="bundles">
            <div class="container">
                <h2 class="section-title">Beliebte Bundles</h2>

                <div class="bundle-grid">
                    <article class="bundle-card">
                        <div class="bundle-img">🥤 🍿 🍫</div>
                        <div class="bundle-body">
                            <h3>Filmabend</h3>
                            <p>Für den perfekten Filmabend.</p>

                            <div class="price-row">
                                <span class="price">15,99 €</span>
                                <button class="plus" type="button" aria-label="Filmabend hinzufügen">+</button>
                            </div>
                        </div>
                    </article>

                    <article class="bundle-card">
                        <div class="bundle-img">⚡ 🥤 🍟</div>
                        <div class="bundle-body">
                            <h3>Zockerpaket</h3>
                            <p>Für lange Gaming-Nächte.</p>

                            <div class="price-row">
                                <span class="price">17,49 €</span>
                                <button class="plus" type="button" aria-label="Zockerpaket hinzufügen">+</button>
                            </div>
                        </div>
                    </article>

                    <article class="bundle-card">
                        <div class="bundle-img">🥤 🍾 🍟</div>
                        <div class="bundle-body">
                            <h3>Partyretter</h3>
                            <p>Deine Party. Unser Nachschub.</p>

                            <div class="price-row">
                                <span class="price">19,99 €</span>
                                <button class="plus" type="button" aria-label="Partyretter hinzufügen">+</button>
                            </div>
                        </div>
                    </article>

                    <article class="bundle-card">
                        <div class="bundle-img">🍬 🍫 🍭</div>
                        <div class="bundle-body">
                            <h3>Süße Tüte</h3>
                            <p>Für alle Naschkatzen.</p>

                            <div class="price-row">
                                <span class="price">9,99 €</span>
                                <button class="plus" type="button" aria-label="Süße Tüte hinzufügen">+</button>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="center">
                    <a class="small-btn" href="#find">Alle Bundles ansehen</a>
                </div>
            </div>
        </section>

        <section class="partner-cta" id="partner">
            <div class="container">
                <div class="partner-banner">
                    <div class="partner-banner__content">
                        <h2>
                            Du betreibst einen <span>Kiosk</span>
                            oder <br> <span>Getränkedienst?</span>
                        </h2>

                        <p>
                            Werde Kioskheld-Partner und erreiche mehr Kunden mit deinem eigenen Online-Shop.
                        </p>

                        <a class="partner-banner__button" href="{{ route('partner.index') }}">
                            Mehr erfahren
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
                        <strong>Über 1.000 Produkte</strong>
                        <span>von deinen Lieblingsmarken</span>
                    </div>
                </div>

                <div class="benefit">
                    <span class="i">♡</span>
                    <div>
                        <strong>Regional & lokal</strong>
                        <span>Dein Kiosk in der Nähe</span>
                    </div>
                </div>

                <div class="benefit">
                    <span class="i">☏</span>
                    <div>
                        <strong>Kundenservice</strong>
                        <span>Wir sind für dich da</span>
                    </div>
                </div>

                <div class="benefit">
                    <span class="i">▣</span>
                    <div>
                        <strong>Sicher bezahlen</strong>
                        <span>100% geschützt</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-marketing.footer />

    <div class="toast" id="toast" role="status" aria-live="polite"></div>
@endsection
