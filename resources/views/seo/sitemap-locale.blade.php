{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}

<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xhtml="http://www.w3.org/1999/xhtml"
>
@foreach ($urls as $url)
    <url>
        <loc>{{ $url['location'] }}</loc>

@foreach ($url['alternates'] as $alternateLocale => $alternateUrl)
        <xhtml:link
            rel="alternate"
            hreflang="{{ $alternateLocale }}"
            href="{{ $alternateUrl }}"
        />
@endforeach

@if ($url['x_default'])
        <xhtml:link
            rel="alternate"
            hreflang="x-default"
            href="{{ $url['x_default'] }}"
        />
@endif
    </url>
@endforeach
</urlset>
