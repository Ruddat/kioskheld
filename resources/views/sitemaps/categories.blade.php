{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($categories as $category)
@php
    $slug = is_string($category)
        ? $category
        : $category->slug;

    $lastmod = is_string($category)
        ? null
        : ($category->source_updated_at ?? $category->updated_at);
@endphp
    <url>
        <loc>{{ route('catalog.categories.show', ['locale' => 'de', 'categorySlug' => $slug]) }}</loc>
@if ($lastmod)
        <lastmod>{{ $lastmod->utc()->toAtomString() }}</lastmod>
@endif
    </url>
@endforeach
</urlset>
