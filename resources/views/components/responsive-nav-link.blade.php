@props(['active' => false])

@php
$extra_classes = $active ?
    'border-blue-400 dark:border-blue-300 text-blue-600 dark:text-blue-300 bg-blue-100 dark:bg-gray-700 focus:bg-indigo-100 dark:focus:bg-indigo-900' :
    'border-transparent text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-300 hover:bg-blue-100 dark:hover:bg-gray-700 hover:border-blue-400 dark:hover:border-blue-300 focus:bg-gray-200 dark:focus:bg-gray-700';
@endphp

<a {{ $attributes->merge(['class' => 'block w-full ps-3 pe-4 py-2 transition duration-150 ease-in-out border-l-4 focus:border-indigo-700 dark:focus:border-indigo-300 focus:text-indigo-800 dark:focus:text-indigo-200 focus:outline-none ' .$extra_classes]) }}>{{ $slot }}</a>
