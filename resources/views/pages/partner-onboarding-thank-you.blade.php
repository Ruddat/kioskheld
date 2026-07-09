@extends('layouts.marketing')

@section('title', 'Startdaten erhalten')

@section('content')
    <main class="partner-thank-you-page">
        <section class="partner-register-section">
            <div class="container narrow">
                <p class="eyebrow">Startdaten erhalten</p>

                <h1>Danke. Deine Kioskheld-Startdaten wurden übermittelt.</h1>

                <p class="lead">
                    Wir prüfen deine Angaben und bereiten die nächsten Schritte vor.
                    Falls etwas fehlt, melden wir uns direkt bei dir.
                </p>

                <a href="{{ route('home') }}" class="btn btn-secondary">
                    Zurück zur Startseite
                </a>
            </div>
        </section>
    </main>
@endsection
