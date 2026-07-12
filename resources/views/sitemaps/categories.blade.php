{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($categories as $category)
    <url>
        <loc>{{ route('catalog.categories.show', ['locale' => 'de', 'categorySlug' => $category->slug]) }}</loc>
        <lastmod>{{ ($category->source_updated_at ?? $category->updated_at)->utc()->toAtomString() }}</lastmod>
    </url>
@endforeach
</urlset>
