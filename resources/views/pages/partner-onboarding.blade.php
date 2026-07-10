@extends('layouts.marketing')

@section('title', 'Kioskheld Startformular')

@section('content')
    <main class="partner-onboarding-page">
        <section class="onboarding-hero">
            <div class="container onboarding-hero-grid">
                <div class="onboarding-hero-copy">
                    <p class="onboarding-kicker">Kioskheld Startformular</p>

                    <h1>
                        Fast geschafft.
                        <span>Jetzt machen wir deinen Kiosk startklar.</span>
                    </h1>

                    <p>
                        Wir haben deine Basisdaten vorbereitet. Ergänze jetzt Sortiment, Öffnungszeiten,
                        Liefergebiet und Zahlungsarten. Danach prüfen wir deine Angaben und bereiten den Start vor.
                    </p>
                </div>

                <aside class="onboarding-hero-card">
                    <span>Startprüfung</span>

                    <h2>Was wir danach prüfen</h2>

                    <ul>
                        <li>Kioskdaten und Kontakt</li>
                        <li>Sortiment und Top-Produkte</li>
                        <li>Liefergebiet und Mindestbestellwert</li>
                        <li>Zahlungsarten und Konditionen</li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="partner-register-section onboarding-section">
            <div class="container onboarding-layout">
                <aside class="onboarding-sidebar">
                    <div class="onboarding-sidebar-card">
                        <p class="onboarding-kicker onboarding-kicker-dark">Dein Ablauf</p>

                        <h2>In wenigen Minuten vollständig.</h2>

                        <p>
                            Je sauberer die Angaben sind, desto schneller können wir deinen Kiosk für den Online-Start vorbereiten.
                        </p>

                        <div class="onboarding-steps">
                            <a href="#kioskdaten">
                                <span>01</span>
                                <strong>Kioskdaten</strong>
                            </a>

                            <a href="#sortiment">
                                <span>02</span>
                                <strong>Sortiment</strong>
                            </a>

                            <a href="#oeffnungszeiten">
                                <span>03</span>
                                <strong>Öffnungszeiten</strong>
                            </a>

                            <a href="#lieferung">
                                <span>04</span>
                                <strong>Lieferung</strong>
                            </a>

                            <a href="#zahlung">
                                <span>05</span>
                                <strong>Zahlung</strong>
                            </a>

                            <a href="#konditionen">
                                <span>06</span>
                                <strong>Konditionen</strong>
                            </a>
                        </div>
                    </div>
                </aside>

                <form method="POST" action="{{ route('partner.onboarding.store', $onboarding->token) }}" class="partner-form onboarding-form">
                    @csrf

                    <section class="onboarding-form-section" id="kioskdaten">
                        <div class="onboarding-section-head">
                            <span>01</span>
                            <div>
                                <h2>Kioskdaten</h2>
                                <p>Diese Angaben erscheinen später als Grundlage für deinen Kioskheld-Start.</p>
                            </div>
                        </div>

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
                    </section>

                    <section class="onboarding-form-section" id="sortiment">
                        <div class="onboarding-section-head">
                            <span>02</span>
                            <div>
                                <h2>Sortiment</h2>
                                <p>Wähle die wichtigsten Kategorien und ergänze deine starken Produkte.</p>
                            </div>
                        </div>

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
                    </section>

                    <section class="onboarding-form-section" id="oeffnungszeiten">
                        <div class="onboarding-section-head">
                            <span>03</span>
                            <div>
                                <h2>Öffnungszeiten</h2>
                                <p>Diese Zeiten helfen uns, deinen Shop korrekt vorzubereiten.</p>
                            </div>
                        </div>

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
                    </section>

                    <section class="onboarding-form-section" id="lieferung">
                        <div class="onboarding-section-head">
                            <span>04</span>
                            <div>
                                <h2>Lieferung</h2>
                                <p>Lege fest, ob du lieferst und welche Basiswerte gelten sollen.</p>
                            </div>
                        </div>

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
                    </section>

                    <section class="onboarding-form-section" id="zahlung">
                        <div class="onboarding-section-head">
                            <span>05</span>
                            <div>
                                <h2>Zahlung</h2>
                                <p>Wähle, welche Zahlungsarten für deinen Kiosk möglich sind.</p>
                            </div>
                        </div>

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
                    </section>

                    <section class="onboarding-form-section" id="konditionen">
                        <div class="onboarding-section-head">
                            <span>06</span>
                            <div>
                                <h2>Konditionen bestätigen</h2>
                                <p>Zum Abschluss brauchen wir deine Bestätigung.</p>
                            </div>
                        </div>

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

                        <button type="submit" class="btn btn-primary full-button onboarding-submit">
                            Startdaten senden
                        </button>
                    </section>
                </form>
            </div>
        </section>
    </main>

    <x-marketing.footer />
@endsection
