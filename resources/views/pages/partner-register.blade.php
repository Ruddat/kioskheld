@extends('layouts.marketing')

@section('title', __('partner.register.meta_title'))

@section('content')
    <main class="partner-register-page">
        <div class="shop-app-nav-wrap">
            <x-marketing.nav />
        </div>

        <section class="partner-register-hero">
            <div class="container partner-register-grid">
                <div class="partner-register-copy">
                    <p class="eyebrow">
                        {{ __('partner.register.eyebrow') }}
                    </p>

                    <h1>
                        {{ __('partner.register.headline') }}
                        <span>{{ __('partner.register.headline_accent') }}</span>
                    </h1>

                    <p class="lead">
                        {{ __('partner.register.lead') }}
                    </p>

                    <div
                        class="register-trust-list"
                        aria-label="{{ __('partner.register.benefits_label') }}"
                    >
                        <article>
                            <span>1</span>

                            <div>
                                <strong>
                                    {{ __('partner.register.benefits.request.title') }}
                                </strong>

                                <p>
                                    {{ __('partner.register.benefits.request.text') }}
                                </p>
                            </div>
                        </article>

                        <article>
                            <span>2</span>

                            <div>
                                <strong>
                                    {{ __('partner.register.benefits.review.title') }}
                                </strong>

                                <p>
                                    {{ __('partner.register.benefits.review.text') }}
                                </p>
                            </div>
                        </article>

                        <article>
                            <span>3</span>

                            <div>
                                <strong>
                                    {{ __('partner.register.benefits.onboarding.title') }}
                                </strong>

                                <p>
                                    {{ __('partner.register.benefits.onboarding.text') }}
                                </p>
                            </div>
                        </article>
                    </div>
                </div>

                <div class="partner-register-card">
                    <div class="form-head">
                        <span>{{ __('partner.register.form.badge') }}</span>

                        <h2>{{ __('partner.register.form.title') }}</h2>

                        <p>{{ __('partner.register.form.intro') }}</p>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('partner.store') }}"
                        class="partner-register-form"
                    >
                        @csrf

                        <div class="form-grid">
                            <label>
                                <span>{{ __('partner.register.form.business_name') }}</span>

                                <input
                                    type="text"
                                    name="business_name"
                                    value="{{ old('business_name') }}"
                                    autocomplete="organization"
                                    required
                                >

                                @error('business_name')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <label>
                                <span>{{ __('partner.register.form.contact_name') }}</span>

                                <input
                                    type="text"
                                    name="contact_name"
                                    value="{{ old('contact_name') }}"
                                    autocomplete="name"
                                >

                                @error('contact_name')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <label>
                                <span>{{ __('partner.register.form.phone') }}</span>

                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    autocomplete="tel"
                                    required
                                >

                                @error('phone')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <label>
                                <span>{{ __('partner.register.form.email') }}</span>

                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                >

                                @error('email')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <label class="full">
                                <span>{{ __('partner.register.form.street') }}</span>

                                <input
                                    type="text"
                                    name="street"
                                    value="{{ old('street') }}"
                                    autocomplete="street-address"
                                >

                                @error('street')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <label>
                                <span>{{ __('partner.register.form.postcode') }}</span>

                                <input
                                    type="text"
                                    name="postcode"
                                    value="{{ old('postcode') }}"
                                    maxlength="5"
                                    inputmode="numeric"
                                    autocomplete="postal-code"
                                    required
                                >

                                @error('postcode')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <label>
                                <span>{{ __('partner.register.form.city') }}</span>

                                <input
                                    type="text"
                                    name="city"
                                    value="{{ old('city') }}"
                                    autocomplete="address-level2"
                                >

                                @error('city')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <label class="full">
                                <span>{{ __('partner.register.form.opening_hours') }}</span>

                                <textarea
                                    name="opening_hours_note"
                                    rows="3"
                                    placeholder="{{ __('partner.register.form.opening_hours_placeholder') }}"
                                >{{ old('opening_hours_note') }}</textarea>

                                @error('opening_hours_note')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <label class="full">
                                <span>{{ __('partner.register.form.delivery_possible') }}</span>

                                <select name="delivery_possible" required>
                                    <option
                                        value="maybe"
                                        @selected(old('delivery_possible', 'maybe') === 'maybe')
                                    >
                                        {{ __('partner.register.form.delivery_maybe') }}
                                    </option>

                                    <option
                                        value="yes"
                                        @selected(old('delivery_possible') === 'yes')
                                    >
                                        {{ __('partner.register.form.delivery_yes') }}
                                    </option>

                                    <option
                                        value="no"
                                        @selected(old('delivery_possible') === 'no')
                                    >
                                        {{ __('partner.register.form.delivery_no') }}
                                    </option>
                                </select>

                                @error('delivery_possible')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>

                            <label class="full">
                                <span>{{ __('partner.register.form.message') }}</span>

                                <textarea
                                    name="message"
                                    rows="4"
                                    placeholder="{{ __('partner.register.form.message_placeholder') }}"
                                >{{ old('message') }}</textarea>

                                @error('message')
                                    <small>{{ $message }}</small>
                                @enderror
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary full-button">
                            {{ __('partner.register.form.submit') }}
                        </button>

                        <p class="form-note">
                            {{ __('partner.register.form.note') }}
                        </p>
                    </form>
                </div>
            </div>
        </section>

        <section class="register-bottom-strip">
            <div class="container register-bottom-grid">
                <div>
                    <strong>
                        {{ __('partner.register.facts.fee.title') }}
                    </strong>

                    <span>
                        {{ __('partner.register.facts.fee.text') }}
                    </span>
                </div>

                <div>
                    <strong>
                        {{ __('partner.register.facts.postcode.title') }}
                    </strong>

                    <span>
                        {{ __('partner.register.facts.postcode.text') }}
                    </span>
                </div>

                <div>
                    <strong>
                        {{ __('partner.register.facts.catalog.title') }}
                    </strong>

                    <span>
                        {{ __('partner.register.facts.catalog.text') }}
                    </span>
                </div>
            </div>
        </section>

        <x-marketing.footer />
    </main>
@endsection
