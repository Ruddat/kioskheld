@extends('layouts.marketing')

@section('title', 'Startdaten erhalten | Kioskheld')

@section('content')
    <main class="partner-onboarding-thank-you-page">
        <section class="onboarding-thank-you-hero">
            <div class="container onboarding-thank-you-grid">
                <div class="onboarding-thank-you-copy">
                    <p class="onboarding-thank-you-kicker">Startdaten erhalten</p>

                    <h1>
                        Danke.
                        <span>Deine Kioskheld-Startdaten sind angekommen.</span>
                    </h1>

                    <p>
                        Wir prüfen jetzt deine Angaben und bereiten die nächsten Schritte für deinen Kioskheld-Start vor.
                        Falls etwas fehlt oder unklar ist, melden wir uns direkt bei dir.
                    </p>

                    <div class="onboarding-thank-you-actions">
                        <a href="{{ route('home') }}" class="onboarding-thank-you-btn onboarding-thank-you-btn-primary">
                            Zur Startseite
                        </a>

                        <a href="{{ route('partner.index') }}" class="onboarding-thank-you-btn onboarding-thank-you-btn-outline">
                            Partnerseite ansehen
                        </a>
                    </div>
                </div>

                <aside class="onboarding-thank-you-card">
                    <div class="onboarding-success-icon">
                        ✓
                    </div>

                    <span>Übermittlung erfolgreich</span>

                    <h2>Was jetzt passiert</h2>

                    <div class="onboarding-next-steps">
                        <div>
                            <strong>01</strong>
                            <p>Wir prüfen Kioskdaten, Sortiment, Lieferung und Zahlungsarten.</p>
                        </div>

                        <div>
                            <strong>02</strong>
                            <p>Falls Rückfragen entstehen, melden wir uns direkt bei dir.</p>
                        </div>

                        <div>
                            <strong>03</strong>
                            <p>Danach bereiten wir deinen Kiosk für den digitalen Start vor.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section class="onboarding-thank-you-section">
            <div class="container">
                <div class="onboarding-thank-you-panel">
                    <div>
                        <p class="onboarding-thank-you-kicker onboarding-thank-you-kicker-dark">Nächster Schritt</p>

                        <h2>Halte Telefon oder WhatsApp erreichbar.</h2>

                        <p>
                            Wenn Angaben fehlen oder wir Details zum Sortiment, Liefergebiet oder zu Zahlungsarten brauchen,
                            melden wir uns über die hinterlegten Kontaktdaten.
                        </p>
                    </div>

                    <div class="onboarding-contact-hint">
                        <span>Hinweis</span>
                        <strong>Je vollständiger deine Angaben sind, desto schneller geht der Start.</strong>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-marketing.footer />
@endsection
