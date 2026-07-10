@extends('layouts.marketing')

@section('title', 'Kioskheld Partner werden')

@section('content')
    <main class="partner-page">
        <div class="shop-app-nav-wrap">
            <x-marketing.nav />
        </div>

        <section class="partner-hero">
            <div class="container partner-hero-grid">
                <div class="partner-hero-content">
                    <p class="eyebrow">Für Kioske, Spätis & Getränkemärkte</p>

                    <h1>
                        Dein Kiosk.
                        <span>Online sichtbar.</span>
                        <strong>Bestellungen direkt zu dir.</strong>
                    </h1>

                    <p class="lead">
                        Kioskheld bringt deinen Kiosk online – mit eigener Bestellseite, PLZ-Suche,
                        vorbereitetem Sortiment und einfacher Verwaltung über Foodzwerge.
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('partner.register') }}" class="btn btn-primary">
                            Kostenlos Partner werden
                        </a>

                        <a href="#how-it-works" class="btn btn-secondary">
                            So läuft der Start
                        </a>
                    </div>

                    <div class="partner-hero-facts" aria-label="Kioskheld Partner Vorteile">
                        <div>
                            <span>⚡</span>
                            <strong>Schneller Start</strong>
                            <small>Grundstruktur vorbereitet</small>
                        </div>

                        <div>
                            <span>🛒</span>
                            <strong>Online verkaufen</strong>
                            <small>Snacks, Getränke & mehr</small>
                        </div>

                        <div>
                            <span>📍</span>
                            <strong>Regional gefunden</strong>
                            <small>über PLZ und Standort</small>
                        </div>
                    </div>
                </div>

                <aside class="partner-hero-panel" aria-label="Partner Startpaket">
                    <div class="partner-bag-card">
                        <div class="partner-bag-top">
                            <span class="mini-label">Kioskheld Startpaket</span>
                            <span class="badge">3 %</span>
                        </div>

                        <h2>Alles vorbereitet für deinen digitalen Kiosk.</h2>

                        <ul>
                            <li>
                                <span>✓</span>
                                Shop-Grundstruktur
                            </li>
                            <li>
                                <span>✓</span>
                                Kiosk-Sortiment als Vorlage
                            </li>
                            <li>
                                <span>✓</span>
                                Öffnungszeiten & Liefergebiet
                            </li>
                            <li>
                                <span>✓</span>
                                Barzahlung und Kartenzahlung erfassen
                            </li>
                            <li>
                                <span>✓</span>
                                spätere Freischaltung nach Prüfung
                            </li>
                        </ul>

                        <a href="{{ route('partner.register') }}" class="partner-panel-link">
                            Anfrage starten →
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
                        <strong>Getränke</strong>
                    </div>

                    <div>
                        <span>⚡</span>
                        <strong>Energy</strong>
                    </div>

                    <div>
                        <span>🍟</span>
                        <strong>Snacks</strong>
                    </div>

                    <div>
                        <span>🍬</span>
                        <strong>Süßes</strong>
                    </div>

                    <div>
                        <span>🍦</span>
                        <strong>Eis</strong>
                    </div>

                    <div>
                        <span>🎁</span>
                        <strong>Bundles</strong>
                    </div>

                    <div>
                        <span>％</span>
                        <strong>Angebote</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="partner-section" id="how-it-works">
            <div class="container">
                <div class="section-heading">
                    <p class="eyebrow">So funktioniert der Partnerstart</p>
                    <h2>Aus einer Anfrage wird dein Kioskheld-Shop.</h2>
                    <p>
                        Erst simpel registrieren. Danach bekommst du einen persönlichen Startlink,
                        mit dem du Sortiment, Öffnungszeiten, Liefergebiet und Zahlungsarten ergänzen kannst.
                    </p>
                </div>

                <div class="steps-grid">
                    <article>
                        <span>1</span>
                        <h3>Anfrage senden</h3>
                        <p>
                            Du trägst nur Kioskname, Kontakt, PLZ und Telefonnummer ein.
                            Mehr braucht es für den ersten Schritt nicht.
                        </p>
                    </article>

                    <article>
                        <span>2</span>
                        <h3>Startformular ausfüllen</h3>
                        <p>
                            Du wählst Kategorien, Öffnungszeiten, Liefer-PLZ, Mindestbestellwert
                            und Zahlungsarten aus.
                        </p>
                    </article>

                    <article>
                        <span>3</span>
                        <h3>Wir bereiten alles vor</h3>
                        <p>
                            Dein Kiosk wird mit Grundstruktur vorbereitet und vor der Freischaltung
                            noch einmal geprüft.
                        </p>
                    </article>

                    <article>
                        <span>4</span>
                        <h3>Bestellungen erhalten</h3>
                        <p>
                            Kunden finden dich über Kioskheld und bestellen direkt bei deinem Kiosk.
                        </p>
                    </article>
                </div>
            </div>
        </section>

<section class="partner-benefits">
    <div class="container partner-benefits-grid">
        <div class="benefit-copy">
            <p class="eyebrow">Warum Kioskheld?</p>

            <h2>
                Online bestellen.
                <span>Ohne Technik-Stress.</span>
            </h2>

            <p>
                Kioskheld ist für lokale Kioske gebaut, die schnell online sichtbar werden wollen –
                ohne komplizierte Einrichtung, ohne eigene Shop-Technik und ohne lange Einarbeitung.
            </p>

            <div class="benefit-metrics" aria-label="Kioskheld Vorteile">
                <div>
                    <strong>3 %</strong>
                    <span>Partnergebühr</span>
                </div>

                <div>
                    <strong>PLZ</strong>
                    <span>regionale Suche</span>
                </div>

                <div>
                    <strong>Start</strong>
                    <span>mit Vorlage</span>
                </div>
            </div>
        </div>

        <div class="benefit-list">
            <article>
                <span class="benefit-icon">⚡</span>
                <div>
                    <strong>Schneller Einstieg</strong>
                    <p>
                        Erst nur Basisdaten senden. Danach bekommst du ein persönliches Startformular
                        für Sortiment, Öffnungszeiten und Lieferung.
                    </p>
                </div>
            </article>

            <article>
                <span class="benefit-icon">🛒</span>
                <div>
                    <strong>Gemacht für Kiosk-Sortiment</strong>
                    <p>
                        Getränke, Energy, Snacks, Süßes, Eis, Bundles und Angebote sind direkt
                        als Struktur vorbereitet.
                    </p>
                </div>
            </article>

            <article>
                <span class="benefit-icon">📍</span>
                <div>
                    <strong>Regional gefunden werden</strong>
                    <p>
                        Kunden suchen über ihre Postleitzahl und finden passende Kioske in ihrer Nähe.
                    </p>
                </div>
            </article>

            <article>
                <span class="benefit-icon">✅</span>
                <div>
                    <strong>Kontrollierter Start</strong>
                    <p>
                        Nichts geht ungeprüft live. Deine Angaben werden vorbereitet, kontrolliert
                        und erst danach freigeschaltet.
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
                        <p class="eyebrow">Bereit für den Start?</p>
                        <h2>Mach deinen Kiosk online bestellbar.</h2>
                        <p>
                            Registrierung absenden, kurz prüfen lassen und dann Schritt für Schritt starten.
                        </p>
                    </div>

                    <a href="{{ route('partner.register') }}" class="btn btn-primary">
                        Jetzt Partner werden
                    </a>
                </div>
            </div>
        </section>

        <x-marketing.footer />
    </main>
@endsection
