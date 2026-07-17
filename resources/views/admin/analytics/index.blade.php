@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
    @php
        $overview = $analytics['overview'];
        $postcodeFunnel = $analytics['postcode_funnel'];
    @endphp

    <main class="admin-content">
        <section class="admin-page-header">
            <div>
                <p class="admin-eyebrow">Kioskheld Analytics</p>
                <h1>Besucher und Performance</h1>
                <p>
                    Auswertung der Seitenaufrufe, Besucher, Bots,
                    Zugriffsquellen und Antwortzeiten.
                </p>
            </div>

            <form method="GET" action="{{ route('admin.analytics.index') }}">
                <label>
                    <span class="sr-only">Zeitraum</span>

                    <select name="days" onchange="this.form.submit()">
                        @foreach ([1 => 'Heute', 7 => '7 Tage', 30 => '30 Tage', 90 => '90 Tage'] as $value => $label)
                            <option value="{{ $value }}" @selected($analytics['days'] === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </form>
        </section>

        <section class="admin-grid analytics-overview">
            <article class="admin-stat-card">
                <span>Seitenaufrufe</span>
                <strong>{{ number_format($overview['total'], 0, ',', '.') }}</strong>
                <small>Menschen und Bots</small>
            </article>

            <article class="admin-stat-card">
                <span>Echte Aufrufe</span>
                <strong>{{ number_format($overview['humans'], 0, ',', '.') }}</strong>
                <small>Als menschlich erkannt</small>
            </article>

            <article class="admin-stat-card">
                <span>Besucher</span>
                <strong>{{ number_format($overview['unique_visitors'], 0, ',', '.') }}</strong>
                <small>Eindeutige Besucher-Hashes</small>
            </article>

            <article class="admin-stat-card">
                <span>Bots</span>
                <strong>{{ number_format($overview['bots'], 0, ',', '.') }}</strong>
                <small>{{ number_format($overview['bot_rate'], 1, ',', '.') }} % Bot-Anteil</small>
            </article>

            <article class="admin-stat-card">
                <span>Antwortzeit</span>
                <strong>{{ number_format($overview['avg_response_time'], 0, ',', '.') }} ms</strong>
                <small>Durchschnitt aller Aufrufe</small>
            </article>
        </section>

<section class="analytics-section">
    <header class="analytics-section-header">
        <div>
            <h2>PLZ-Funnel</h2>
            <p>
                Suchanfragen und Verfügbarkeit im gewählten Zeitraum.
            </p>
        </div>
    </header>

    <div class="admin-grid analytics-overview">
        <article class="admin-stat-card">
            <span>PLZ-Suchen</span>
            <strong>
                {{ number_format($postcodeFunnel['searched'], 0, ',', '.') }}
            </strong>
            <small>Gestartete Verfügbarkeitsprüfungen</small>
        </article>

        <article class="admin-stat-card">
            <span>Verfügbar</span>
            <strong>
                {{ number_format($postcodeFunnel['available'], 0, ',', '.') }}
            </strong>
            <small>Kiosk oder Liefergebiet gefunden</small>
        </article>

        <article class="admin-stat-card">
            <span>Nicht verfügbar</span>
            <strong>
                {{ number_format($postcodeFunnel['unavailable'], 0, ',', '.') }}
            </strong>
            <small>Noch kein Angebot vorhanden</small>
        </article>

        <article class="admin-stat-card">
            <span>Fehlgeschlagen</span>
            <strong>
                {{ number_format($postcodeFunnel['failed'], 0, ',', '.') }}
            </strong>
            <small>Technische Fehler oder API-Ausfälle</small>
        </article>

        <article class="admin-stat-card">
            <span>Verfügbarkeitsquote</span>
            <strong>
                {{ number_format($postcodeFunnel['availability_rate'], 1, ',', '.') }} %
            </strong>
            <small>Verfügbar von erfolgreich geprüften PLZ</small>
        </article>
    </div>
</section>

        <section class="analytics-panels">


<article class="analytics-panel analytics-panel-wide">
    <header>
        <h2>Gesuchte Postleitzahlen</h2>
        <p>
            Zeigt Nachfrage sowie vorhandene und fehlende Abdeckung.
        </p>
    </header>

    @if ($analytics['top_postcodes'] === [])
        <p class="analytics-empty">
            Noch keine PLZ-Suchen vorhanden.
        </p>
    @else
        <div class="analytics-table-wrapper">
            <table class="analytics-table">
                <thead>
                    <tr>
                        <th>PLZ</th>
                        <th>Suchen</th>
                        <th>Verfügbar</th>
                        <th>Nicht verfügbar</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($analytics['top_postcodes'] as $postcode)
                        <tr>
                            <td>
                                <strong>{{ $postcode['postcode'] }}</strong>
                            </td>
                            <td>
                                {{ number_format($postcode['searches'], 0, ',', '.') }}
                            </td>
                            <td>
                                {{ number_format($postcode['available'], 0, ',', '.') }}
                            </td>
                            <td>
                                {{ number_format($postcode['unavailable'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</article>











            <article class="analytics-panel">
                <header>
                    <h2>Top-Routen</h2>
                    <p>Häufigste echte Seitenaufrufe</p>
                </header>

                @if ($analytics['top_routes'] === [])
                    <p class="analytics-empty">Noch keine Daten vorhanden.</p>
                @else
                    <div class="analytics-table-wrapper">
                        <table class="analytics-table">
                            <thead>
                                <tr>
                                    <th>Route</th>
                                    <th>Aufrufe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($analytics['top_routes'] as $route)
                                    <tr>
                                        <td>{{ $route['route'] }}</td>
                                        <td>{{ number_format($route['count'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </article>

            <article class="analytics-panel">
                <header>
                    <h2>Statuscodes</h2>
                    <p>HTTP-Antworten im gewählten Zeitraum</p>
                </header>

                @if ($analytics['status_codes'] === [])
                    <p class="analytics-empty">Noch keine Daten vorhanden.</p>
                @else
                    <div class="analytics-status-list">
                        @foreach ($analytics['status_codes'] as $status => $count)
                            <div>
                                <span>HTTP {{ $status }}</span>
                                <strong>{{ number_format($count, 0, ',', '.') }}</strong>
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>


            <article class="analytics-panel">
                <header>
                    <h2>Langsamste Routen</h2>
                    <p>Nur Routen mit mindestens drei Aufrufen</p>
                </header>

                @if ($analytics['slowest_routes'] === [])
                    <p class="analytics-empty">Noch nicht genügend Daten vorhanden.</p>
                @else
                    <div class="analytics-table-wrapper">
                        <table class="analytics-table">
                            <thead>
                                <tr>
                                    <th>Route</th>
                                    <th>Durchschnitt</th>
                                    <th>Aufrufe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($analytics['slowest_routes'] as $route)
                                    <tr>
                                        <td>{{ $route['route'] }}</td>
                                        <td>{{ number_format($route['avg_ms'], 0, ',', '.') }} ms</td>
                                        <td>{{ number_format($route['count'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </article>

            <article class="analytics-panel">
                <header>
                    <h2>Top-Bots</h2>
                    <p>Erkannte Crawler und Scanner</p>
                </header>

                @if ($analytics['top_bots'] === [])
                    <p class="analytics-empty">Keine Bots erkannt.</p>
                @else
                    <div class="analytics-table-wrapper">
                        <table class="analytics-table">
                            <thead>
                                <tr>
                                    <th>Bot</th>
                                    <th>Aufrufe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($analytics['top_bots'] as $bot)
                                    <tr>
                                        <td>{{ $bot['name'] }}</td>
                                        <td>{{ number_format($bot['count'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </article>

            <article class="analytics-panel analytics-panel-wide">
                <header>
                    <h2>Referrer</h2>
                    <p>Webseiten, von denen Besucher zu Kioskheld kamen</p>
                </header>

                @if ($analytics['top_referers'] === [])
                    <p class="analytics-empty">Noch keine externen Referrer vorhanden.</p>
                @else
                    <div class="analytics-table-wrapper">
                        <table class="analytics-table">
                            <thead>
                                <tr>
                                    <th>Quelle</th>
                                    <th>Aufrufe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($analytics['top_referers'] as $referer)
                                    <tr>
                                        <td>{{ $referer['referer'] }}</td>
                                        <td>{{ number_format($referer['count'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </article>
        </section>
    </main>
@endsection
