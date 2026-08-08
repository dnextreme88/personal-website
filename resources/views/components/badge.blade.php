@props([
    'as' => 'span',
    'variant' => 'neutral',
    'interactive' => false,
])

{{-- Reusable rounded pill/badge. Variants:
     - neutral : high-contrast bordered grey (readable, default — e.g. table tags).
     - cyan    : neon cyan text/border + faint glow.
     - magenta : neon magenta text/border + faint glow.
     - none    : structure only; the caller drives colours (e.g. an Alpine toggle
                 via x-bind:class).
     `interactive` adds the pointer affordance for clickable badges. All extra
     attributes (x-text, x-on:click, x-bind:class, wire:*, :key, extra classes)
     pass through via $attributes->merge. --}}
@php
    $base = 'inline-block px-2 py-1 border-2 rounded-xl text-sm font-subtext transition-colors duration-200';

    $variants = [
        'neutral' => 'text-gray-800 dark:text-gray-200 border-gray-800 dark:border-gray-200',
        'cyan' => 'badge-neon-cyan text-cyan-700 dark:text-cyan-200 border-cyan-700 dark:border-cyan-300',
        'magenta' => 'badge-neon-magenta text-fuchsia-700 dark:text-fuchsia-200 border-fuchsia-700 dark:border-fuchsia-300',
        'none' => '',
    ];

    $classes = trim($base.' '.($variants[$variant] ?? $variants['neutral']).($interactive ? ' hover:cursor-pointer' : ''));
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</{{ $as }}>
