@props([
    'label' => '',
    'value' => null,
])

<div class="bg-gray-200 dark:bg-gray-800 p-4 rounded-lg shadow">
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>

    @isset($value)
        <p class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $value }}</p>
    @else
        <div class="text-gray-800 dark:text-gray-200">{{ $slot }}</div>
    @endisset
</div>
