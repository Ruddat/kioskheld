@extends('layouts.marketing')

@section('title', 'Als Kioskheld-Partner registrieren')

@section('content')
    <main class="partner-register-page">
        <div class="shop-app-nav-wrap">
            <x-marketing.nav />
        </div>

        <section class="partner-register-section">
            <div class="container form-layout">
                <div>
                    <p class="eyebrow">Partner werden</p>

                    <h1>Starte mit deinem Kiosk auf Kioskheld.</h1>

                    <p class="lead">
                        Trag kurz deine Daten ein. Wir bereiten deinen Kiosk vor und melden uns,
                        bevor etwas live geschaltet wird.
                    </p>
                </div>

                <form method="POST" action="{{ route('partner.store') }}" class="partner-form">
                    @csrf

                    <div class="form-grid">
                        <label>
                            <span>Kioskname *</span>
                            <input type="text" name="business_name" value="{{ old('business_name') }}" required>
                            @error('business_name') <small>{{ $message }}</small> @enderror
                        </label>

                        <label>
                            <span>Ansprechpartner</span>
                            <input type="text" name="contact_name" value="{{ old('contact_name') }}">
                            @error('contact_name') <small>{{ $message }}</small> @enderror
                        </label>

                        <label>
                            <span>Telefon / WhatsApp *</span>
                            <input type="text" name="phone" value="{{ old('phone') }}" required>
                            @error('phone') <small>{{ $message }}</small> @enderror
                        </label>

                        <label>
                            <span>E-Mail</span>
                            <input type="email" name="email" value="{{ old('email') }}">
                            @error('email') <small>{{ $message }}</small> @enderror
                        </label>

                        <label class="full">
                            <span>Straße</span>
                            <input type="text" name="street" value="{{ old('street') }}">
                            @error('street') <small>{{ $message }}</small> @enderror
                        </label>

                        <label>
                            <span>PLZ *</span>
                            <input type="text" name="postcode" value="{{ old('postcode') }}" maxlength="5" inputmode="numeric" required>
                            @error('postcode') <small>{{ $message }}</small> @enderror
                        </label>

                        <label>
                            <span>Ort</span>
                            <input type="text" name="city" value="{{ old('city') }}">
                            @error('city') <small>{{ $message }}</small> @enderror
                        </label>

                        <label class="full">
                            <span>Öffnungszeiten grob</span>
                            <textarea name="opening_hours_note" rows="3">{{ old('opening_hours_note') }}</textarea>
                            @error('opening_hours_note') <small>{{ $message }}</small> @enderror
                        </label>

                        <label class="full">
                            <span>Kannst du selbst liefern?</span>
                            <select name="delivery_possible" required>
                                <option value="maybe" @selected(old('delivery_possible', 'maybe') === 'maybe')>Vielleicht / müssen wir klären</option>
                                <option value="yes" @selected(old('delivery_possible') === 'yes')>Ja</option>
                                <option value="no" @selected(old('delivery_possible') === 'no')>Nein</option>
                            </select>
                            @error('delivery_possible') <small>{{ $message }}</small> @enderror
                        </label>

                        <label class="full">
                            <span>Nachricht</span>
                            <textarea name="message" rows="4">{{ old('message') }}</textarea>
                            @error('message') <small>{{ $message }}</small> @enderror
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary full-button">
                        Kostenlos als Partner starten
                    </button>
                </form>
            </div>
        </section>
    </main>
@endsection
