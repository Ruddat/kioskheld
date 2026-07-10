@extends('layouts.admin')

@section('title', 'Partner-Anfrage: ' . $partnerLead->business_name)

@section('content')
    @php
        $latestOnboarding = $partnerLead->latestOnboarding;
    @endphp

    <main class="admin-page">
        <section class="admin-section">
            <div class="container">
                <div class="admin-header">
                    <div>
                        <p class="eyebrow">Partner-Anfrage</p>

                        <h1>{{ $partnerLead->business_name }}</h1>

                        <p class="lead">
                            Eingegangen am
                            {{ $partnerLead->created_at?->format('d.m.Y H:i') }}
                            Uhr.
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.partner-leads.index') }}"
                        class="btn btn-secondary"
                    >
                        Zurück zur Übersicht
                    </a>
                </div>

                @if (session('status'))
                    <div class="admin-alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="admin-alert admin-alert-error">
                        {{ session('error') }}
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
                                        <a href="mailto:{{ $partnerLead->email }}">
                                            {{ $partnerLead->email }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt>Adresse</dt>
                                <dd>
                                    {{ $partnerLead->street ?: '—' }}<br>
                                    {{ $partnerLead->postcode }}
                                    {{ $partnerLead->city }}
                                </dd>
                            </div>
                        </dl>
                    </article>

                    <article class="admin-card">
                        <h2>Status</h2>

                        <form
                            method="POST"
                            action="{{ route('admin.partner-leads.update-status', $partnerLead) }}"
                            class="status-form"
                        >
                            @csrf
                            @method('PATCH')

                            <label>
                                <span>Bearbeitungsstatus</span>

                                <select name="status">
                                    <option value="new" @selected($partnerLead->status === 'new')>
                                        Neu
                                    </option>

                                    <option value="contacted" @selected($partnerLead->status === 'contacted')>
                                        Kontaktiert
                                    </option>

                                    <option value="in_review" @selected($partnerLead->status === 'in_review')>
                                        In Prüfung
                                    </option>

                                    <option value="converted" @selected($partnerLead->status === 'converted')>
                                        Konvertiert
                                    </option>

                                    <option value="rejected" @selected($partnerLead->status === 'rejected')>
                                        Abgelehnt
                                    </option>
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
                                    {{ match ($partnerLead->delivery_possible) {
                                        'yes' => 'Ja',
                                        'no' => 'Nein',
                                        default => 'Vielleicht / zu klären',
                                    } }}
                                </dd>
                            </div>

                            <div>
                                <dt>Öffnungszeiten</dt>
                                <dd>
                                    {{ $partnerLead->opening_hours_note ?: '—' }}
                                </dd>
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
                                <dd>
                                    {{ data_get($partnerLead->metadata, 'ip', '—') }}
                                </dd>
                            </div>

                            <div>
                                <dt>User-Agent</dt>
                                <dd class="break-text">
                                    {{ data_get($partnerLead->metadata, 'user_agent', '—') }}
                                </dd>
                            </div>
                        </dl>
                    </article>

                    <article class="admin-card admin-card-wide">
                        <div class="admin-card-header">
                            <div>
                                <h2>Onboarding</h2>

                                <p class="admin-muted">
                                    Startdaten, JustDeliver-Import und Betriebsstatus.
                                </p>
                            </div>
                        </div>

                        @if ($latestOnboarding)
                            <section class="admin-onboarding-section">
                                <h3>Onboarding-Status</h3>

                                <dl class="admin-dl">
                                    <div>
                                        <dt>Status</dt>
                                        <dd>
                                            {{ match ($latestOnboarding->status) {
                                                'sent' => 'Link versendet',
                                                'submitted' => 'Startdaten abgesendet',
                                                default => $latestOnboarding->status ?: '—',
                                            } }}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>Onboarding-Link</dt>
                                        <dd class="break-text">
                                            <a
                                                href="{{ route('partner.onboarding.show', $latestOnboarding->token) }}"
                                                target="_blank"
                                                rel="noopener"
                                            >
                                                {{ route('partner.onboarding.show', $latestOnboarding->token) }}
                                            </a>
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>Ablauf</dt>
                                        <dd>
                                            {{ $latestOnboarding->expires_at?->format('d.m.Y H:i') ?? '—' }}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt>Abgesendet</dt>
                                        <dd>
                                            {{ $latestOnboarding->submitted_at?->format('d.m.Y H:i') ?? 'Noch nicht abgesendet' }}
                                        </dd>
                                    </div>
                                </dl>
                            </section>

                            <hr class="admin-separator">

                            <section class="admin-onboarding-section">
                                <h3>JustDeliver-Import</h3>

                                <dl class="admin-dl">
                                    <div>
                                        <dt>Importstatus</dt>
                                        <dd>
                                            {{ match ($latestOnboarding->justdeliver_import_status) {
                                                'pending' => 'Noch nicht übertragen',
                                                'importing' => 'Übertragung läuft',
                                                'imported' => 'Erfolgreich übertragen',
                                                'failed' => 'Übertragung fehlgeschlagen',
                                                default => $latestOnboarding->justdeliver_import_status ?: 'Noch nicht übertragen',
                                            } }}
                                        </dd>
                                    </div>

                                    @if ($latestOnboarding->justdeliver_shop_id)
                                        <div>
                                            <dt>JustDeliver-Shop</dt>
                                            <dd>
                                                ID:
                                                {{ $latestOnboarding->justdeliver_shop_id }}<br>

                                                Slug:
                                                {{ $latestOnboarding->justdeliver_shop_slug ?: '—' }}
                                            </dd>
                                        </div>
                                    @endif

                                    @if ($latestOnboarding->justdeliver_imported_at)
                                        <div>
                                            <dt>Zuletzt übertragen</dt>
                                            <dd>
                                                {{ $latestOnboarding->justdeliver_imported_at->format('d.m.Y H:i') }}
                                            </dd>
                                        </div>
                                    @endif

                                    @if ($latestOnboarding->justdeliver_import_error)
                                        <div>
                                            <dt>Importfehler</dt>
                                            <dd class="break-text">
                                                {{ $latestOnboarding->justdeliver_import_error }}
                                            </dd>
                                        </div>
                                    @endif
                                </dl>

                                @if ($latestOnboarding->isSubmitted())
                                    <div class="admin-import-box">
                                        <div>
                                            <h4>Shop-Entwurf übertragen</h4>

                                            <p class="admin-muted">
                                                Die Daten werden als nicht veröffentlichter
                                                und nicht bestellbarer Shop-Entwurf an
                                                JustDeliver übertragen.
                                            </p>
                                        </div>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.partner-onboardings.import', $latestOnboarding) }}"
                                            class="admin-action-form"
                                            onsubmit="return confirm('Onboarding jetzt an JustDeliver übertragen?');"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-primary"
                                                @disabled($latestOnboarding->justdeliver_import_status === 'importing')
                                            >
                                                @if ($latestOnboarding->justdeliver_import_status === 'imported')
                                                    Erneut an JustDeliver übertragen
                                                @elseif ($latestOnboarding->justdeliver_import_status === 'failed')
                                                    Übertragung erneut versuchen
                                                @elseif ($latestOnboarding->justdeliver_import_status === 'importing')
                                                    Übertragung läuft …
                                                @else
                                                    An JustDeliver übertragen
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </section>

                            @if ($latestOnboarding->justdeliver_shop_id)
                                <hr class="admin-separator">

                                <section class="admin-onboarding-section">
                                    <h3>JustDeliver-Betriebsstatus</h3>

                                    <dl class="admin-dl">
                                        <div>
                                            <dt>Remote-Status</dt>
                                            <dd>
                                                {{ match ($latestOnboarding->justdeliver_remote_status) {
                                                    'active' => 'Aktiv',
                                                    'draft' => 'Entwurf',
                                                    'pending' => 'In Vorbereitung',
                                                    'failed' => 'Fehlgeschlagen',
                                                    default => $latestOnboarding->justdeliver_remote_status ?: 'Noch nicht abgefragt',
                                                } }}
                                            </dd>
                                        </div>

                                        <div>
                                            <dt>Produkte importierbar</dt>
                                            <dd>
                                                {{ $latestOnboarding->justdeliver_can_import_products ? 'Ja' : 'Nein' }}
                                            </dd>
                                        </div>

                                        <div>
                                            <dt>Bestellungen möglich</dt>
                                            <dd>
                                                @if ($latestOnboarding->justdeliver_can_accept_orders)
                                                    <strong>
                                                        Ja – Shop ist bestellbereit
                                                    </strong>
                                                @else
                                                    Nein
                                                @endif
                                            </dd>
                                        </div>

                                        <div>
                                            <dt>Zuletzt geprüft</dt>
                                            <dd>
                                                {{ $latestOnboarding->justdeliver_status_checked_at?->format('d.m.Y H:i') ?? 'Noch nicht geprüft' }}
                                            </dd>
                                        </div>

                                        @if ($latestOnboarding->justdeliver_activated_at)
                                            <div>
                                                <dt>Bestellbereit seit</dt>
                                                <dd>
                                                    {{ $latestOnboarding->justdeliver_activated_at->format('d.m.Y H:i') }}
                                                </dd>
                                            </div>
                                        @endif

                                        @if ($latestOnboarding->justdeliver_status_error)
                                            <div>
                                                <dt>Statusfehler</dt>
                                                <dd class="break-text">
                                                    {{ $latestOnboarding->justdeliver_status_error }}
                                                </dd>
                                            </div>
                                        @endif
                                    </dl>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.partner-onboardings.status', $latestOnboarding) }}"
                                        class="admin-action-form"
                                    >
                                        @csrf

                                        <button type="submit" class="btn btn-secondary">
                                            JustDeliver-Status aktualisieren
                                        </button>
                                    </form>
                                </section>
                            @endif

                            @if ($latestOnboarding->isSubmitted())
                                <hr class="admin-separator">

                                <section class="admin-onboarding-section">
                                    <h3>Ausgefüllte Startdaten</h3>

                                    <dl class="admin-dl">
                                        <div>
                                            <dt>Kategorien</dt>
                                            <dd>
                                                {{ implode(
                                                    ', ',
                                                    data_get(
                                                        $latestOnboarding->selected_categories,
                                                        'categories',
                                                        []
                                                    )
                                                ) ?: '—' }}
                                            </dd>
                                        </div>

                                        <div>
                                            <dt>Weitere Kategorien</dt>
                                            <dd>
                                                {{ data_get(
                                                    $latestOnboarding->selected_categories,
                                                    'custom_categories',
                                                    '—'
                                                ) ?: '—' }}
                                            </dd>
                                        </div>

                                        <div>
                                            <dt>Top-Produkte</dt>
                                            <dd>
                                                {{ data_get(
                                                    $latestOnboarding->selected_categories,
                                                    'top_products',
                                                    '—'
                                                ) ?: '—' }}
                                            </dd>
                                        </div>

                                        <div>
                                            <dt>Lieferung</dt>
                                            <dd>
                                                {{ match (data_get(
                                                    $latestOnboarding->delivery_settings,
                                                    'enabled'
                                                )) {
                                                    'yes' => 'Ja',
                                                    'no' => 'Nein',
                                                    default => 'Noch zu klären',
                                                } }}
                                            </dd>
                                        </div>

                                        <div>
                                            <dt>Liefergebiet</dt>
                                            <dd>
                                                PLZ:
                                                {{ data_get(
                                                    $latestOnboarding->delivery_settings,
                                                    'postcodes',
                                                    '—'
                                                ) ?: '—' }}
                                                <br>

                                                Mindestbestellwert:
                                                {{ data_get(
                                                    $latestOnboarding->delivery_settings,
                                                    'minimum_order_value',
                                                    '—'
                                                ) }}
                                                €
                                                <br>

                                                Lieferkosten:
                                                {{ data_get(
                                                    $latestOnboarding->delivery_settings,
                                                    'delivery_fee',
                                                    '—'
                                                ) }}
                                                €
                                                <br>

                                                Kostenlose Lieferung ab:
                                                {{ data_get(
                                                    $latestOnboarding->delivery_settings,
                                                    'free_delivery_from',
                                                    '—'
                                                ) }}
                                                €
                                            </dd>
                                        </div>

                                        <div>
                                            <dt>Zahlung</dt>
                                            <dd>
                                                Barzahlung:
                                                {{ data_get(
                                                    $latestOnboarding->payment_settings,
                                                    'cash_enabled'
                                                ) ? 'Ja' : 'Nein' }}
                                                <br>

                                                Kartenzahlung:
                                                {{ data_get(
                                                    $latestOnboarding->payment_settings,
                                                    'card_enabled'
                                                ) ? 'Ja' : 'Nein' }}
                                                <br>

                                                Karte ab:
                                                {{ data_get(
                                                    $latestOnboarding->payment_settings,
                                                    'card_minimum_order_value',
                                                    '—'
                                                ) }}
                                                €
                                                <br>

                                                Kartengebühr:
                                                {{ data_get(
                                                    $latestOnboarding->payment_settings,
                                                    'card_fee_enabled'
                                                ) ? 'Ja' : 'Nein' }}
                                            </dd>
                                        </div>

                                        <div>
                                            <dt>Konditionen bestätigt</dt>
                                            <dd>
                                                {{ $latestOnboarding->accepted_terms_at?->format('d.m.Y H:i') ?? '—' }}

                                                @if ($latestOnboarding->accepted_terms_ip)
                                                    <br>
                                                    IP:
                                                    {{ $latestOnboarding->accepted_terms_ip }}
                                                @endif
                                            </dd>
                                        </div>
                                    </dl>
                                </section>
                            @endif
                        @else
                            <p class="admin-muted">
                                Für diesen Partner wurde noch kein
                                Onboarding-Link erstellt.
                            </p>
                        @endif

                        <hr class="admin-separator">

                        <form
                            method="POST"
                            action="{{ route('admin.partner-leads.onboarding.store', $partnerLead) }}"
                            class="admin-action-form"
                            onsubmit="return confirm('Neuen Onboarding-Link erstellen?');"
                        >
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
