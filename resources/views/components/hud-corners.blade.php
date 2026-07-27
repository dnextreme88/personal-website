@props(['variant' => 'cyan'])

@php
    $accent = $variant === 'magenta' ? 'hud-corner-magenta' : '';
@endphp

<span aria-hidden="true" class="hud-corner hud-corner-tl {{ $accent }}"></span>
<span aria-hidden="true" class="hud-corner hud-corner-tr {{ $accent }}"></span>
<span aria-hidden="true" class="hud-corner hud-corner-bl {{ $accent }}"></span>
<span aria-hidden="true" class="hud-corner hud-corner-br {{ $accent }}"></span>
