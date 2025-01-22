@props([
    'is_required' => false,
    'value' => '',
])

<label {{ $attributes->merge(['class' => 'block font-semibold text-gray-600 dark:text-gray-200']) }} aria-label="{{ $value }}">
    {{ $value ?? $slot }}

    @if ($is_required)
        <x-red-asterisk />
    @endif
</label>
