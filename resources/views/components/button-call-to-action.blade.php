@props([
    'href' => null,
    'variant' => 'cyan',
    'tag' => null,
    'surface_class' => '',
])

@php
    $element = $tag ?? ($href ? 'a' : 'button');
    $sweep_via = $variant === 'magenta' ? 'via-neon-magenta/30' : 'via-neon-cyan/30';
@endphp

<{{ $element }}
    @if ($element === 'a')
        wire:navigate
        @if ($href) href="{{ $href }}" @endif
    @else
        type="{{ $attributes->get('type', 'button') }}"
    @endif
    {{ $attributes->merge(['class' => 'btn-call-to-action group relative inline-block leading-none']) }}
>
    <span class="relative flex items-center justify-center overflow-hidden clip-notch px-5 py-2.5 font-subtext text-sm font-semibold uppercase tracking-widest text-white">
        {{-- Border — a cyan→magenta gradient filling the whole notch shape. The
             fill layer below is inset by the border width and re-clipped to the
             same notch, so only a thin band of this gradient shows as a border
             that follows the clip path, diagonals included.
             REF: https://stackoverflow.com/a/70238234 --}}
        <span aria-hidden="true" class="pointer-events-none absolute inset-0 bg-linear-to-r from-neon-cyan to-neon-magenta"></span>

        {{-- Fill — inset by the 2px border width, re-clipped to the notch. --}}
        <span aria-hidden="true" class="pointer-events-none absolute inset-0.5 clip-notch bg-gray-800 dark:bg-gray-200 {{ $surface_class }}"></span>

        {{-- Hover sweep, kept inside the fill. --}}
        <span aria-hidden="true" class="pointer-events-none absolute inset-0.5 -translate-x-full bg-linear-to-r from-transparent to-transparent transition-transform duration-800 group-hover:translate-x-full {{ $sweep_via }}"></span>

        <span class="relative z-10 text-gray-200">{{ $slot }}</span>
    </span>
</{{ $element }}>
