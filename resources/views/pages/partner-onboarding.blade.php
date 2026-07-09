@extends('layouts.marketing')

@section('title', 'Kioskheld Startformular')

@section('content')
    <main class="partner-onboarding-page">
        <section class="partner-register-section">
            <div class="container form-layout">
                <div>
                    <p class="eyebrow">Kioskheld Startformular</p>

                    <h1>Fast geschafft. Ergänze deine Startdaten.</h1>

                    <p class="lead">
                        Wir haben deine Basisdaten bereits vorbereitet. Bitte prüfe die Angaben und ergänze Sortiment,
                        Öffnungszeiten, Lieferung und Zahlungsarten.
                    </p>
                </div>

                <form method="POST" action="{{ route('partner.onboarding.store', $onboarding->token) }}" class="partner-form">
                    @csrf

                    <h2>1. Kioskdaten</h2>

                    <div class="form-grid">
                        <label>
                            <span>Kioskname *</span>
                            <input type="text" name="business_name" value="{{ old('business_name', data_get($onboarding->business_data, 'business_name', $lead->business_name)) }}" required>
                            @error('business_name') <small>{{ $message }}</small> @enderror
                        </label>

                        <label>
                            <span>Ansprechpartner</span>
                            <input type="text" name="contact_name" value="{{ old('contact_name', data_get($onboarding->business_data, 'contact_name', $lead->contact_name)) }}">
                            @error('contact_name') <small>{{ $message }}</small> @enderror
                        </label>

                        <label>
                            <span>Telefon / WhatsApp *</span>
                            <input type="text" name="phone" value="{{ old('phone', data_get($onboarding->business_data, 'phone', $lead->phone)) }}" required>
                            @error('phone') <small>{{ $message }}</small> @enderror
                        </label>

                        <label>
                            <span>E-Mail</span>
                            <input type="email" name="email" value="{{ old('email', data_get($onboarding->business_data, 'email', $lead->email)) }}">
                            @error('email') <small>{{ $message }}</small> @enderror
                        </label>

                        <label class="full">
                            <span>Straße</span>
                            <input type="text" name="street" value="{{ old('street', data_get($onboarding->business_data, 'street', $lead->street)) }}">
                            @error('street') <small>{{ $message }}</small> @enderror
                        </label>

                        <label>
                            <span>PLZ *</span>
                            <input type="text" name="postcode" value="{{ old('postcode', data_get($onboarding->business_data, 'postcode', $lead->postcode)) }}" maxlength="5" inputmode="numeric" required>
                            @error('postcode') <small>{{ $message }}</small> @enderror
                        </label>

                        <label>
                            <span>Ort</span>
                            <input type="text" name="city" value="{{ old('city', data_get($onboarding->business_data, 'city', $lead->city)) }}">
                            @error('city') <small>{{ $message }}</small> @enderror
                        </label>
                    </div>

                    <h2>2. Sortiment</h2>

                    <div class="checkbox-grid">
                        @foreach ($categories as $category)
                            <label class="check-card">
                                <input type="checkbox" name="categories[]" value="{{ $category }}" @checked(in_array($category, old('categories', []), true))>
                                <span>{{ $category }}</span>
                            </label>
                        @endforeach
                    </div>

                    <label class="form-block">
                        <span>Eigene Kategorien oder Besonderheiten</span>
                        <textarea name="custom_categories" rows="3">{{ old('custom_categories') }}</textarea>
                    </label>

                    <label class="form-block">
                        <span>Welche Produkte laufen bei dir besonders gut?</span>
                        <textarea name="top_products" rows="4" placeholder="z. B. Red Bull, Chips, Zigaretten, Bier, Süßigkeiten ...">{{ old('top_products') }}</textarea>
                    </label>

                    <h2>3. Öffnungszeiten</h2>

                    <div class="opening-grid">
                        @foreach ([
                            'monday' => 'Montag',
                            'tuesday' => 'Dienstag',
                            'wednesday' => 'Mittwoch',
                            'thursday' => 'Donnerstag',
                            'friday' => 'Freitag',
                            'saturday' => 'Samstag',
                            'sunday' => 'Sonntag',
                        ] as $dayKey => $dayLabel)
                            <div class="opening-row">
                                <label class="opening-active">
                                    <input type="checkbox" name="opening_hours[{{ $dayKey }}][open]" value="1" checked>
                                    <strong>{{ $dayLabel }}</strong>
                                </label>

                                <input type="time" name="opening_hours[{{ $dayKey }}][from]" value="{{ old("opening_hours.$dayKey.from", '10:00') }}">
                                <input type="time" name="opening_hours[{{ $dayKey }}][to]" value="{{ old("opening_hours.$dayKey.to", '23:00') }}">
                            </div>
                        @endforeach
                    </div>

                    <h2>4. Lieferung</h2>

                    <div class="form-grid">
                        <label class="full">
                            <span>Lieferst du selbst?</span>
                            <select name="delivery_enabled">
                                <option value="maybe" @selected(old('delivery_enabled', $lead->delivery_possible) === 'maybe')>Vielleicht / müssen wir klären</option>
                                <option value="yes" @selected(old('delivery_enabled', $lead->delivery_possible) === 'yes')>Ja</option>
                                <option value="no" @selected(old('delivery_enabled', $lead->delivery_possible) === 'no')>Nein</option>
                            </select>
                        </label>

                        <label class="full">
                            <span>Liefer-PLZ</span>
                            <input type="text" name="delivery_postcodes" value="{{ old('delivery_postcodes', $lead->postcode) }}" placeholder="z. B. 31234, 31224, 31226">
                        </label>

                        <label>
                            <span>Mindestbestellwert</span>
                            <input type="number" step="0.01" min="0" name="minimum_order_value" value="{{ old('minimum_order_value', '15.00') }}">
                        </label>

                        <label>
                            <span>Lieferkosten</span>
                            <input type="number" step="0.01" min="0" name="delivery_fee" value="{{ old('delivery_fee', '2.50') }}">
                        </label>

                        <label>
                            <span>Kostenlose Lieferung ab</span>
                            <input type="number" step="0.01" min="0" name="free_delivery_from" value="{{ old('free_delivery_from') }}">
                        </label>
                    </div>

                    <h2>5. Zahlung</h2>

                    <div class="checkbox-grid">
                        <label class="check-card">
                            <input type="checkbox" name="cash_enabled" value="1" checked>
                            <span>Barzahlung akzeptieren</span>
                        </label>

                        <label class="check-card">
                            <input type="checkbox" name="card_enabled" value="1">
                            <span>EC-/Kartenzahlung an der Tür möglich</span>
                        </label>
                    </div>

                    <div class="form-grid">
                        <label>
                            <span>Kartenzahlung ab</span>
                            <input type="number" step="0.01" min="0" name="card_minimum_order_value" value="{{ old('card_minimum_order_value') }}">
                        </label>

                        <label>
                            <span>Kartengebühr</span>
                            <input type="number" step="0.01" min="0" name="card_fee_amount" value="{{ old('card_fee_amount') }}">
                        </label>

                        <label class="full check-line">
                            <input type="checkbox" name="card_fee_enabled" value="1">
                            <span>Kartengebühr verwenden, sofern zulässig und vereinbart</span>
                        </label>
                    </div>

                    <h2>6. Konditionen bestätigen</h2>

                    <div class="terms-box">
                        <p>
                            Kioskheld vermittelt Bestellungen für deinen Kiosk. Für vermittelte Bestellungen fällt eine
                            Servicegebühr von <strong>3 %</strong> auf den Bestellwert an. Die Freischaltung erfolgt erst
                            nach Prüfung der Angaben.
                        </p>

                        <label>
                            <input type="checkbox" name="accept_terms" value="1" required>
                            <span>Ich bestätige die Kioskheld-Partnerkonditionen.</span>
                        </label>

                        <label>
                            <input type="checkbox" name="confirm_data" value="1" required>
                            <span>Ich bestätige, dass meine Angaben korrekt sind.</span>
                        </label>

                        <label>
                            <input type="checkbox" name="confirm_authorized" value="1" required>
                            <span>Ich bin berechtigt, diese Angaben für den Kiosk zu übermitteln.</span>
                        </label>

                        @error('accept_terms') <small>{{ $message }}</small> @enderror
                        @error('confirm_data') <small>{{ $message }}</small> @enderror
                        @error('confirm_authorized') <small>{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary full-button">
                        Startdaten senden
                    </button>
                </form>
            </div>
        </section>
    </main>
@endsection
