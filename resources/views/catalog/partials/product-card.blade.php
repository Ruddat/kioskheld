<article class="product-card">
    <a
        class="product-card__link"
        href="{{ route('catalog.products.show', [
            'locale' => app()->getLocale(),
            'productSlug' => $product->slug,
        ]) }}"
    >
        <div class="product-card__media">
            @if (filled($product->image_url))
                <img
                    class="product-card__image"
                    src="{{ $product->image_url }}"
                    alt=""
                    loading="lazy"
                    decoding="async"
                    onerror="
                        this.hidden = true;
                        this.nextElementSibling.hidden = false;
                    "
                >

                <div
                    class="product-card__placeholder"
                    hidden
                    aria-hidden="true"
                >
                    {{ mb_strtoupper(
                        mb_substr($product->name, 0, 1)
                    ) }}
                </div>
            @else
                <div
                    class="product-card__placeholder"
                    aria-hidden="true"
                >
                    {{ mb_strtoupper(
                        mb_substr($product->name, 0, 1)
                    ) }}
                </div>
            @endif

            @if ($product->lowest_price !== null)
                <span class="product-card__price">
                    Ab {{ number_format(
                        (float) $product->lowest_price,
                        2,
                        ',',
                        '.'
                    ) }} €
                </span>
            @endif
        </div>

        <div class="product-card__body">
            @if ($product->brand)
                <p class="product-card__brand">
                    {{ $product->brand }}
                </p>
            @endif

            <h3 class="product-card__title">
                {{ $product->name }}
            </h3>

            @if ($product->short_description)
                <p class="product-card__description">
                    {{ \Illuminate\Support\Str::limit(
                        $product->short_description,
                        105
                    ) }}
                </p>
            @elseif ($product->description)
                <p class="product-card__description">
                    {{ \Illuminate\Support\Str::limit(
                        strip_tags($product->description),
                        105
                    ) }}
                </p>
            @endif

            <div class="product-card__footer">
                <span>Produkt ansehen</span>

                <span
                    class="product-card__arrow"
                    aria-hidden="true"
                >
                    →
                </span>
            </div>
        </div>
    </a>
</article>
