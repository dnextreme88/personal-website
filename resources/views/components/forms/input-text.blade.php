@props([
    'for',
    'placeholder_text' => null,
])

<input
    wire:model="{{ $for }}"
    type="text"
    {{ $attributes->merge(['class' => 'block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-blue-400']) }}
    {{ $placeholder_text ? 'placeholder=' .$placeholder_text. ' aria-placeholder=' .$placeholder_text. '' : '' }}
/>
