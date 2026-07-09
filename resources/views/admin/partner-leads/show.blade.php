@extends('layouts.admin')

@section('title', 'Partner-Anfrage: ' . $partnerLead->business_name)

@section('content')
    <main class="admin-page">
        <section class="admin-section">
            <div class="container">
                <div class="admin-header">
                    <div>
                        <p class="eyebrow">Partner-Anfrage</p>
                        <h1>{{ $partnerLead->business_name }}</h1>
                        <p class="lead">
                            Eingegangen am {{ $partnerLead->created_at?->format('d.m.Y H:i') }} Uhr.
                        </p>
                    </div>

                    <a href="{{ route('admin.partner-leads.index') }}" class="btn btn-secondary">
                        Zurück zur Übersicht
                    </a>
                </div>

                @if (session('status'))
                    <div class="admin-alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="admin-detail-grid">
                    <article class="admin-card">
                        <h2>Kontaktdaten</h2>

                        <dl class="admin-dl">
                            <div>
                                <dt>Kioskname</dt>
                                <dd>{{ $partnerLead->business_name }}</dd>
                            </div>

                            <div>
                                <dt>Ansprechpartner</dt>
                                <dd>{{ $partnerLead->contact_name ?: '—' }}</dd>
                            </div>

                            <div>
                                <dt>Telefon / WhatsApp</dt>
                                <dd>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $partnerLead->phone) }}">
                                        {{ $partnerLead->phone }}
                                    </a>
                                </dd>
                            </div>

                            <div>
                                <dt>E-Mail</dt>
                                <dd>
                                    @if ($partnerLead->email)
                                        <a href="mailto:{{ $partnerLead->email }}">{{ $partnerLead->email }}</a>
                                    @else
                                        —
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt>Adresse</dt>
                                <dd>
                                    {{ $partnerLead->street ?: '—' }}<br>
                                    {{ $partnerLead->postcode }} {{ $partnerLead->city }}
                                </dd>
                            </div>
                        </dl>
                    </article>

                    <article class="admin-card">
                        <h2>Status</h2>

                        <form method="POST" action="{{ route('admin.partner-leads.update-status', $partnerLead) }}" class="status-form">
                            @csrf
                            @method('PATCH')

                            <label>
                                <span>Bearbeitungsstatus</span>

                                <select name="status">
                                    <option value="new" @selected($partnerLead->status === 'new')>Neu</option>
                                    <option value="contacted" @selected($partnerLead->status === 'contacted')>Kontaktiert</option>
                                    <option value="in_review" @selected($partnerLead->status === 'in_review')>In Prüfung</option>
                                    <option value="converted" @selected($partnerLead->status === 'converted')>Konvertiert</option>
                                    <option value="rejected" @selected($partnerLead->status === 'rejected')>Abgelehnt</option>
                                </select>

                                @error('status')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <button type="submit" class="btn btn-primary">
                                Status speichern
                            </button>
                        </form>
                    </article>

                    <article class="admin-card">
                        <h2>Betrieb</h2>

                        <dl class="admin-dl">
                            <div>
                                <dt>Lieferung möglich</dt>
                                <dd>
                                    {{ match($partnerLead->delivery_possible) {
                                        'yes' => 'Ja',
                                        'no' => 'Nein',
                                        default => 'Vielleicht / zu klären',
                                    } }}
                                </dd>
                            </div>

                            <div>
                                <dt>Öffnungszeiten</dt>
                                <dd>{{ $partnerLead->opening_hours_note ?: '—' }}</dd>
                            </div>

                            <div>
                                <dt>Nachricht</dt>
                                <dd>{{ $partnerLead->message ?: '—' }}</dd>
                            </div>
                        </dl>
                    </article>

                    <article class="admin-card">
                        <h2>Technische Daten</h2>

                        <dl class="admin-dl">
                            <div>
                                <dt>Quelle</dt>
                                <dd>{{ $partnerLead->source }}</dd>
                            </div>

                            <div>
                                <dt>IP</dt>
                                <dd>{{ data_get($partnerLead->metadata, 'ip', '—') }}</dd>
                            </div>

                            <div>
                                <dt>User-Agent</dt>
                                <dd class="break-text">{{ data_get($partnerLead->metadata, 'user_agent', '—') }}</dd>
                            </div>
                        </dl>
                    </article>

<article class="admin-card">
    <h2>Onboarding</h2>

    @php
        $latestOnboarding = $partnerLead->latestOnboarding;
    @endphp

    @if ($latestOnboarding)
        <dl class="admin-dl">
            <div>
                <dt>Status</dt>
                <dd>{{ $latestOnboarding->status }}</dd>
            </div>

            <div>
                <dt>Link</dt>
                <dd class="break-text">
                    <a href="{{ route('partner.onboarding.show', $latestOnboarding->token) }}" target="_blank">
                        {{ route('partner.onboarding.show', $latestOnboarding->token) }}
                    </a>
                </dd>
            </div>

            <div>
                <dt>Ablauf</dt>
                <dd>{{ $latestOnboarding->expires_at?->format('d.m.Y H:i') ?? '—' }}</dd>
            </div>

            <div>
                <dt>Abgesendet</dt>
                <dd>{{ $latestOnboarding->submitted_at?->format('d.m.Y H:i') ?? 'Noch nicht abgesendet' }}</dd>
            </div>
        </dl>

        @if ($latestOnboarding->isSubmitted())
            <hr class="admin-separator">

            <h3>Ausgefüllte Startdaten</h3>

            <dl class="admin-dl">
                <div>
                    <dt>Kategorien</dt>
                    <dd>
                        {{ implode(', ', data_get($latestOnboarding->selected_categories, 'categories', [])) ?: '—' }}
                    </dd>
                </div>

                <div>
                    <dt>Top-Produkte</dt>
                    <dd>{{ data_get($latestOnboarding->selected_categories, 'top_products', '—') ?: '—' }}</dd>
                </div>

                <div>
                    <dt>Liefergebiet</dt>
                    <dd>
                        PLZ: {{ data_get($latestOnboarding->delivery_settings, 'postcodes', '—') ?: '—' }}<br>
                        Mindestbestellwert: {{ data_get($latestOnboarding->delivery_settings, 'minimum_order_value', '—') }} €<br>
                        Lieferkosten: {{ data_get($latestOnboarding->delivery_settings, 'delivery_fee', '—') }} €
                    </dd>
                </div>

                <div>
                    <dt>Zahlung</dt>
                    <dd>
                        Barzahlung:
                        {{ data_get($latestOnboarding->payment_settings, 'cash_enabled') ? 'Ja' : 'Nein' }}<br>

                        Kartenzahlung:
                        {{ data_get($latestOnboarding->payment_settings, 'card_enabled') ? 'Ja' : 'Nein' }}<br>

                        Karte ab:
                        {{ data_get($latestOnboarding->payment_settings, 'card_minimum_order_value', '—') }} €
                    </dd>
                </div>

                <div>
                    <dt>Konditionen bestätigt</dt>
                    <dd>
                        {{ $latestOnboarding->accepted_terms_at?->format('d.m.Y H:i') ?? '—' }}
                        @if ($latestOnboarding->accepted_terms_ip)
                            <br>IP: {{ $latestOnboarding->accepted_terms_ip }}
                        @endif
                    </dd>
                </div>
            </dl>
        @endif
    @else
        <p class="admin-muted">
            Für diesen Partner wurde noch kein Onboarding-Link erstellt.
        </p>
    @endif

    <form method="POST" action="{{ route('admin.partner-leads.onboarding.store', $partnerLead) }}" class="admin-action-form">
        @csrf

        <button type="submit" class="btn btn-primary">
            Neuen Onboarding-Link erstellen
        </button>
    </form>
</article>



                </div>
            </div>
        </section>
    </main>
@endsection
