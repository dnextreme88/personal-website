@props([
    'for',
    'placeholder_text' => null,
    'title_text' => null,
])

<input
    wire:model="{{ $for }}"
    type="text"
    {{ $attributes->merge(['class' => 'outline outline-1 outline-gray-300 placeholder:text-gray-400 cyber-input']) }}
    @if ($placeholder_text) placeholder="{{ $placeholder_text }}" aria-placeholder="{{ $placeholder_text }}" @endif
    @if ($title_text) title="{{ $title_text }}" @endif
/>
