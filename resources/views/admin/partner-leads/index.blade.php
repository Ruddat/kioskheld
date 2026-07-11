@extends('layouts.admin')

@section('title', 'Partner-Anfragen')

@section('content')
    <main class="admin-content">
        <section class="admin-section">
            <div class="admin-header">
                <div>
                    <p class="admin-eyebrow">Kioskheld Admin</p>
                    <h1>Partner-Anfragen</h1>
                    <p class="lead">
                        Eingegangene Kiosk-Registrierungen und Interessenten.
                    </p>
                </div>

<a
    href="{{ route('partner.index', ['locale' => config('app.locale', 'de')]) }}"
    class="btn btn-secondary"
    target="_blank"
    rel="noopener"
>
    Partnerseite ansehen
</a>
            </div>

            <div class="admin-card">
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Kiosk</th>
                                <th>Kontakt</th>
                                <th>Telefon</th>
                                <th>PLZ / Ort</th>
                                <th>Lieferung</th>
                                <th>Status</th>
                                <th>Eingang</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($partnerLeads as $lead)
                                <tr>
                                    <td>
                                        <strong>{{ $lead->business_name }}</strong>

                                        @if ($lead->street)
                                            <span>{{ $lead->street }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $lead->contact_name ?: '—' }}

                                        @if ($lead->email)
                                            <span>{{ $lead->email }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="tel:{{ preg_replace('/\s+/', '', $lead->phone) }}">
                                            {{ $lead->phone }}
                                        </a>
                                    </td>

                                    <td>
                                        {{ $lead->postcode }}

                                        @if ($lead->city)
                                            {{ $lead->city }}
                                        @endif
                                    </td>

                                    <td>
                                        {{ match($lead->delivery_possible) {
                                            'yes' => 'Ja',
                                            'no' => 'Nein',
                                            default => 'Vielleicht',
                                        } }}
                                    </td>

                                    <td>
                                        <span class="status-pill status-{{ $lead->status }}">
                                            {{ match($lead->status) {
                                                'new' => 'Neu',
                                                'contacted' => 'Kontaktiert',
                                                'in_review' => 'In Prüfung',
                                                'converted' => 'Konvertiert',
                                                'rejected' => 'Abgelehnt',
                                                default => $lead->status,
                                            } }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $lead->created_at?->format('d.m.Y H:i') }}
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.partner-leads.show', [
    'partnerLead' => $lead,
]) }}" class="admin-link">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        Noch keine Partner-Anfragen vorhanden.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="admin-pagination">
                    {{ $partnerLeads->links() }}
                </div>
            </div>
        </section>
    </main>
@endsection
