@props([
    'showCrown' => true,
    'href' => null,
])

<a href="{{ $href ?? route('home') }}" {{ $attributes->merge(['class' => 'logo']) }} aria-label="Kioskheld Startseite">
    @if ($showCrown)
        <span class="crown">♛ ♛</span>
    @endif

    <span class="brand">KIOS<span>HELD</span></span>
    <span class="powered">POWERED BY FOODZWERGE</span>
</a>
