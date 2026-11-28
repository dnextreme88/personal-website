@props([
    // Cutout portrait (transparent background) shown in the centre of the ring.
    'src',
    'alt',
    // Native pixel size of `src` (the portrait is square).
    'size' => 1152,
    // Smaller versions for srcset: [path (relative to public/) => pixel width]. `src` is
    // added as the largest candidate. Pair with `sizes` so the browser picks the right one.
    'variants' => [],
    'sizes' => null,
    // Photo paths (relative to public/) for the ring cards. Repeated to fill the ring.
    'photos' => [],
    'cards' => 18,
])

@php
    $srcset = collect($variants)
        ->put($src, $size)
        ->sort()
        ->map(fn ($width, $path) => asset($path).' '.$width.'w')
        ->implode(', ');

    $ring_photos = collect($photos)->values();
    $ring_cards = $ring_photos->isEmpty()
        ? collect()
        : collect(range(0, $cards - 1))->map(fn ($i) => $ring_photos[$i % $ring_photos->count()]);
@endphp

{{-- Black-and-white portrait with a ring of photo cards orbiting around it in 3D.
     The portrait and the cards share one 3D space, so cards behind the portrait are
     hidden by it and cards in front pass over it. --}}
<div {{ $attributes->merge(['class' => 'portrait-ring']) }}>
    <div class="portrait-ring__stage">
        <img
            src="{{ asset($src) }}"
            @if ($variants && $sizes)
                srcset="{{ $srcset }}"
                sizes="{{ $sizes }}"
            @endif
            alt="{{ $alt }}"
            width="{{ $size }}"
            height="{{ $size }}"
            class="portrait-ring__figure"
        />

        @if ($ring_cards->isNotEmpty())
            <div aria-hidden="true" class="portrait-ring__tilt">
                <div class="portrait-ring__orbit animate-ring-orbit" style="--ring-cards: {{ $ring_cards->count() }};">
                    @foreach ($ring_cards as $i => $photo)
                        <figure class="portrait-ring__card" style="--ring-i: {{ $i }};">
                            <img src="{{ asset($photo) }}" alt="" decoding="async" />
                        </figure>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
