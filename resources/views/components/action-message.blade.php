@props([
    'font_size' => 'sm',
    'on', // Name of dispatched event
    'type' => null
])

@php
    switch ($type) {
        case 'success':
            $bg_colors = 'bg-green-600 dark:bg-green-500';

            break;
        case 'error':
            $bg_colors = 'bg-red-600 dark:bg-red-500';

            break;
        default:
            $bg_colors = 'bg-gray-600 dark:bg-gray-500';

            break;
    }
@endphp

<div x-data="{ shown: false, timeout: null }"
    x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
    x-show.transition.out.opacity.duration.1500ms="shown"
    x-transition:leave.opacity.duration.1500ms
    style="display: none;"
    {{ $attributes->merge(['class' => 'text-' .$font_size]) }}>
    {!!
        $slot->isEmpty() ? '<span class="text-green-700 dark:text-green-300">Saved.</span>'
        : '
            <div class="px-4 py-2 text-gray-100 ' .$bg_colors. '">' .$slot. '</div>
        '
    !!}
</div>
