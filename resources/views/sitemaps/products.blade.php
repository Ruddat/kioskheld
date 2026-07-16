{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($products as $product)
    <url>
        <loc>{{ route('catalog.products.show', ['locale' => 'de', 'productSlug' => $product->slug]) }}</loc>
        <lastmod>{{ ($product->source_updated_at ?? $product->updated_at)->utc()->toAtomString() }}</lastmod>
    </url>
@endforeach
</urlset>
