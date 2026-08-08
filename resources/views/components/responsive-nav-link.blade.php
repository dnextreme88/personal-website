@props(['active' => false])

@php
    $extra_classes = $active ?
        'border-neon-cyan text-neon-cyan bg-blue-100 dark:bg-gray-800 focus:bg-indigo-100 dark:focus:bg-indigo-900' :
        'border-transparent text-gray-600 dark:text-gray-400 hover:text-neon-cyan hover:bg-blue-100 dark:hover:bg-gray-700 hover:border-neon-cyan focus:bg-gray-200 dark:focus:bg-gray-700';
@endphp

<a {{ $attributes->merge(['class' => 'block w-full ps-3 pe-4 py-2 font-subtext text-sm uppercase tracking-wider transition duration-150 ease-in-out border-l-4 focus:border-neon-cyan focus:text-neon-cyan focus:outline-none ' .$extra_classes]) }}>{{ $slot }}</a>
