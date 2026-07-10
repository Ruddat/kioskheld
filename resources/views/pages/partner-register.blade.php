@extends('layouts.marketing')

@section('title', 'Als Kioskheld-Partner registrieren')

@section('content')
    <main class="partner-register-page">
        <div class="shop-app-nav-wrap">
            <x-marketing.nav />
        </div>

        <section class="partner-register-hero">
            <div class="container partner-register-grid">
                <div class="partner-register-copy">
                    <p class="eyebrow">Partner werden</p>

                    <h1>
                        Starte deinen Kiosk
                        <span>auf Kioskheld.</span>
                    </h1>

                    <p class="lead">
                        Trag kurz deine Daten ein. Wir prüfen deine Anfrage und bereiten deinen digitalen Kiosk vor.
                        Es wird nichts automatisch live geschaltet.
                    </p>

                    <div class="register-trust-list" aria-label="Vorteile der Kioskheld Registrierung">
                        <article>
                            <span>1</span>
                            <div>
                                <strong>Kurze Anfrage</strong>
                                <p>Kioskname, Kontakt und PLZ reichen für den ersten Schritt.</p>
                            </div>
                        </article>

                        <article>
                            <span>2</span>
                            <div>
                                <strong>Persönliche Prüfung</strong>
                                <p>Wir melden uns bei dir, bevor dein Kiosk eingerichtet wird.</p>
                            </div>
                        </article>

                        <article>
                            <span>3</span>
                            <div>
                                <strong>Startformular danach</strong>
                                <p>Sortiment, Öffnungszeiten und Liefergebiet klären wir im nächsten Schritt.</p>
                            </div>
                        </article>
                    </div>
                </div>

                <div class="partner-register-card">
                    <div class="form-head">
                        <span>Kioskheld Partner</span>
                        <h2>Registrierung</h2>
                        <p>Die Anfrage dauert weniger als eine Minute.</p>
                    </div>

                    <form method="POST" action="{{ route('partner.store') }}" class="partner-register-form">
                        @csrf

                        <div class="form-grid">
                            <label>
                                <span>Kioskname *</span>
                                <input
                                    type="text"
                                    name="business_name"
                                    value="{{ old('business_name') }}"
                                    autocomplete="organization"
                                    required
                                >
                                @error('business_name') <small>{{ $message }}</small> @enderror
                            </label>

                            <label>
                                <span>Ansprechpartner</span>
                                <input
                                    type="text"
                                    name="contact_name"
                                    value="{{ old('contact_name') }}"
                                    autocomplete="name"
                                >
                                @error('contact_name') <small>{{ $message }}</small> @enderror
                            </label>

                            <label>
                                <span>Telefon / WhatsApp *</span>
                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    autocomplete="tel"
                                    required
                                >
                                @error('phone') <small>{{ $message }}</small> @enderror
                            </label>

                            <label>
                                <span>E-Mail</span>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                >
                                @error('email') <small>{{ $message }}</small> @enderror
                            </label>

                            <label class="full">
                                <span>Straße</span>
                                <input
                                    type="text"
                                    name="street"
                                    value="{{ old('street') }}"
                                    autocomplete="street-address"
                                >
                                @error('street') <small>{{ $message }}</small> @enderror
                            </label>

                            <label>
                                <span>PLZ *</span>
                                <input
                                    type="text"
                                    name="postcode"
                                    value="{{ old('postcode') }}"
                                    maxlength="5"
                                    inputmode="numeric"
                                    autocomplete="postal-code"
                                    required
                                >
                                @error('postcode') <small>{{ $message }}</small> @enderror
                            </label>

                            <label>
                                <span>Ort</span>
                                <input
                                    type="text"
                                    name="city"
                                    value="{{ old('city') }}"
                                    autocomplete="address-level2"
                                >
                                @error('city') <small>{{ $message }}</small> @enderror
                            </label>

                            <label class="full">
                                <span>Öffnungszeiten grob</span>
                                <textarea
                                    name="opening_hours_note"
                                    rows="3"
                                    placeholder="z. B. täglich bis 23 Uhr, Sonntag geschlossen ..."
                                >{{ old('opening_hours_note') }}</textarea>
                                @error('opening_hours_note') <small>{{ $message }}</small> @enderror
                            </label>

                            <label class="full">
                                <span>Kannst du selbst liefern?</span>
                                <select name="delivery_possible" required>
                                    <option value="maybe" @selected(old('delivery_possible', 'maybe') === 'maybe')>
                                        Vielleicht / müssen wir klären
                                    </option>
                                    <option value="yes" @selected(old('delivery_possible') === 'yes')>
                                        Ja
                                    </option>
                                    <option value="no" @selected(old('delivery_possible') === 'no')>
                                        Nein
                                    </option>
                                </select>
                                @error('delivery_possible') <small>{{ $message }}</small> @enderror
                            </label>

                            <label class="full">
                                <span>Nachricht</span>
                                <textarea
                                    name="message"
                                    rows="4"
                                    placeholder="Optional: Was sollen wir vorab wissen?"
                                >{{ old('message') }}</textarea>
                                @error('message') <small>{{ $message }}</small> @enderror
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary full-button">
                            Kostenlos als Partner starten
                        </button>

                        <p class="form-note">
                            Deine Anfrage ist unverbindlich. Die Freischaltung erfolgt erst nach persönlicher Prüfung.
                        </p>
                    </form>
                </div>
            </div>
        </section>

        <section class="register-bottom-strip">
            <div class="container register-bottom-grid">
                <div>
                    <strong>3 % Partnergebühr</strong>
                    <span>für vermittelte Bestellungen</span>
                </div>

                <div>
                    <strong>PLZ-basierte Suche</strong>
                    <span>Kunden finden Kioske in ihrer Nähe</span>
                </div>

                <div>
                    <strong>Sortiment vorbereitet</strong>
                    <span>Getränke, Snacks, Süßes, Eis und Bundles</span>
                </div>
            </div>
        </section>

        <x-marketing.footer />
    </main>
@endsection
