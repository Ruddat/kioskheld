@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <main class="admin-content">
        <section class="admin-page-header">
            <div>
                <p class="admin-eyebrow">Kioskheld Admin</p>
                <h1>Dashboard</h1>
                <p>Interne Übersicht für Partner-Anfragen, Shops und Kioskheld-Verwaltung.</p>
            </div>
        </section>

        <section class="admin-grid">
            <a href="{{ route('admin.partner-leads.index') }}" class="admin-stat-card">
                <span>Partner-Anfragen</span>
                <strong>{{ \App\Models\PartnerLead::count() }}</strong>
                <small>Registrierte Kiosk-Interessenten</small>
            </a>
        </section>
    </main>
@endsection
