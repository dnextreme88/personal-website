@props([
    'bevels' => [],
    'size' => '10px',
    'type' => 'submit',
    'surface_class' => '',
])

@php
    // Accept bevels as an array (['tl', 'br']) or a delimited string ("tl br" / "tl,br").
    // Same per-corner bevel logic as the x-clipped-table component.
    $active = is_array($bevels)
        ? $bevels
        : preg_split('/[\s,]+/', trim((string) $bevels), -1, PREG_SPLIT_NO_EMPTY);
    $active = array_map('strtolower', (array) $active);

    $n = $size;

    $tl = in_array('tl', $active, true);
    $tr = in_array('tr', $active, true);
    $bl = in_array('bl', $active, true);
    $br = in_array('br', $active, true);

    // Build the clip-path polygon clockwise from the top-left. Each requested
    // corner becomes a diagonal bevel (two points); the rest stay square.
    $points = [];

    $points[] = $tl ? "{$n} 0" : '0 0';

    if ($tr) {
        $points[] = "calc(100% - {$n}) 0";
        $points[] = "100% {$n}";
    } else {
        $points[] = '100% 0';
    }

    if ($br) {
        $points[] = "100% calc(100% - {$n})";
        $points[] = "calc(100% - {$n}) 100%";
    } else {
        $points[] = '100% 100%';
    }

    if ($bl) {
        $points[] = "{$n} 100%";
        $points[] = "0 calc(100% - {$n})";
    } else {
        $points[] = '0 100%';
    }

    if ($tl) {
        $points[] = "0 {$n}";
    }

    $clip_path = 'polygon(' . implode(', ', $points) . ')';
@endphp

<button
    type="{{ $attributes->get('type', $type) }}"
    {{ $attributes->except('type')->merge(['class' => 'btn-submit group relative inline-block leading-none']) }}
>
    <span
        class="relative flex items-center justify-center overflow-hidden px-5 py-2.5 text-sm font-semibold uppercase tracking-widest text-white dark:text-gray-900"
        style="clip-path: {{ $clip_path }};"
    >
        {{-- Border — solid magenta filling the whole bevelled shape. The fill
             layer below is inset by the border width and re-clipped to the same
             shape, so only a thin magenta band shows as a border that follows the
             clip path, diagonals included.
             REF: https://stackoverflow.com/a/70238234 --}}
        <span aria-hidden="true" class="pointer-events-none absolute inset-0 bg-neon-cyan"></span>

        {{-- Fill — inset by the 2px border width, re-clipped to the same shape. --}}
        <span aria-hidden="true" class="pointer-events-none absolute inset-0.5 bg-cyan-800 dark:bg-cyan-200 {{ $surface_class }}" style="clip-path: {{ $clip_path }};"></span>

        {{-- Hover sweep --}}
        <span aria-hidden="true" class="btn-submit-sweep pointer-events-none absolute inset-0.5 bg-linear-to-r from-transparent to-transparent via-cyan-500"></span>

        <span class="relative z-10 font-loader">{{ $slot }}</span>
    </span>
</button>
