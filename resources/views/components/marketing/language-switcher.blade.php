@php
    use App\Support\Seo\SeoUrl;

    $currentLocale = app()->getLocale();
    $languages = config('localization.languages', []);
@endphp

@if (! empty($languages))
    <div class="language-switcher">
        <button
            type="button"
            class="language-switcher-trigger"
            aria-haspopup="true"
            aria-expanded="false"
            aria-label="{{ __('navigation.language_switcher') }}"
        >
            <span aria-hidden="true">🌐</span>
            <span>{{ strtoupper($currentLocale) }}</span>
            <span aria-hidden="true">▾</span>
        </button>

        <div class="language-switcher-menu">
            @foreach ($languages as $locale => $language)
                <a
                    href="{{ SeoUrl::localizedUrl($locale) }}"
                    class="language-switcher-option {{ $locale === $currentLocale ? 'is-active' : '' }}"
                    lang="{{ $locale }}"
                    hreflang="{{ $locale }}"
                    @if ($locale === $currentLocale)
                        aria-current="page"
                    @endif
                >
                    <span>{{ strtoupper($locale) }}</span>

                    <span>
                        {{ $language['native'] ?? strtoupper($locale) }}
                    </span>

                    @if ($locale === $currentLocale)
                        <span aria-hidden="true">✓</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endif
