@php
    $currentLocale = app()->getLocale();
    $currentRoute = request()->route();
    $routeName = $currentRoute?->getName();
    $routeParameters = $currentRoute?->parameters() ?? [];

    $languages = config('localization.languages', []);
@endphp

@if ($routeName && ! empty($languages))
    <div class="language-switcher">
        <button
            type="button"
            class="language-switcher-trigger"
            aria-haspopup="true"
            aria-expanded="false"
            aria-label="Sprache auswählen"
        >
            <span aria-hidden="true">🌐</span>
            <span>{{ strtoupper($currentLocale) }}</span>
            <span aria-hidden="true">▾</span>
        </button>

        <div class="language-switcher-menu">
            @foreach ($languages as $locale => $language)
                @php
                    $url = route(
                        $routeName,
                        array_merge(
                            $routeParameters,
                            ['locale' => $locale],
                            request()->query(),
                        ),
                    );
                @endphp

                <a
                    href="{{ $url }}"
                    class="language-switcher-option {{ $locale === $currentLocale ? 'is-active' : '' }}"
                    lang="{{ $locale }}"
                    hreflang="{{ $locale }}"
                    @if ($locale === $currentLocale) aria-current="page" @endif
                >
                    <span>{{ strtoupper($locale) }}</span>
                    <span>{{ $language['native'] ?? strtoupper($locale) }}</span>

                    @if ($locale === $currentLocale)
                        <span aria-hidden="true">✓</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endif
