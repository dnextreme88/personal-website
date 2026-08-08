@props([
    'label' => '',
    'value' => null,
])

<div class="p-4 rounded-lg shadow card-rectangle">
    <p class="text-sm text-gray-600 dark:text-gray-400 font-subtext">{{ $label }}</p>

    @isset($value)
        <p class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ $value }}</p>
    @else
        <div class="text-gray-800 dark:text-gray-200">{{ $slot }}</div>
    @endisset
</div>
