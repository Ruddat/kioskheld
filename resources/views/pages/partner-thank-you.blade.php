@extends('layouts.marketing')

@section('title', 'Danke für deine Registrierung')

@section('content')
    <main class="partner-thank-you-page">
        <section class="partner-register-section">
            <div class="container narrow">
                <p class="eyebrow">Registrierung erhalten</p>

                <h1>Danke. Dein Kioskheld-Start ist vorgemerkt.</h1>

                <p class="lead">
                    Wir prüfen deine Angaben und melden uns kurzfristig bei dir.
                    Danach bereiten wir deinen Kiosk vor und klären Sortiment, Lieferung und Starttermin.
                </p>

                <a href="{{ route('partner.index') }}" class="btn btn-secondary">
                    Zurück zur Partnerseite
                </a>
            </div>
        </section>
    </main>
@endsection
