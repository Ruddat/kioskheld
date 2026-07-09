@extends('layouts.marketing')

@section('title', 'Kioskheld Partner werden')

@section('content')
    <main class="partner-page">

        <div class="shop-app-nav-wrap">
            <x-marketing.nav />
        </div>


        <section class="partner-hero">
            <div class="container partner-hero-grid">
                <div>
                    <p class="eyebrow">Für Kioske, Spätis & Getränkemärkte</p>

                    <h1>
                        Werde Kioskheld-Partner und verkaufe online in deiner Region.
                    </h1>

                    <p class="lead">
                        Wir helfen dir, deinen Kiosk digital sichtbar zu machen – mit Online-Bestellung,
                        Lieferoption und einfacher Verwaltung über Foodzwerge.
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('partner.register') }}" class="btn btn-primary">
                            Kostenlos starten
                        </a>

                        <a href="#how-it-works" class="btn btn-secondary">
                            So funktioniert es
                        </a>
                    </div>
                </div>

                <div class="partner-card">
                    <strong>In wenigen Minuten vorbereitet</strong>
                    <ul>
                        <li>Shop-Grundstruktur</li>
                        <li>Kiosk-Sortiment als Vorlage</li>
                        <li>PLZ-basierte Suche</li>
                        <li>Optional mit Lieferung</li>
                        <li>Später Zugang per Magic Link</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="partner-section" id="how-it-works">
            <div class="container">
                <h2>So einfach läuft der Start</h2>

                <div class="steps-grid">
                    <article>
                        <span>1</span>
                        <h3>Daten eintragen</h3>
                        <p>Kioskname, Telefonnummer, PLZ und grobe Öffnungszeiten reichen für den ersten Schritt.</p>
                    </article>

                    <article>
                        <span>2</span>
                        <h3>Wir bereiten deinen Shop vor</h3>
                        <p>Deine Grundstruktur wird vorbereitet: Kategorien, Liefergebiet und Sortiment.</p>
                    </article>

                    <article>
                        <span>3</span>
                        <h3>Du gehst online</h3>
                        <p>Nach kurzer Prüfung kannst du Bestellungen über Kioskheld erhalten.</p>
                    </article>
                </div>

                <div class="center-action">
                    <a href="{{ route('partner.register') }}" class="btn btn-primary">
                        Jetzt Partner werden
                    </a>
                </div>
            </div>
        </section>
                <x-marketing.footer />
    </main>
@endsection
