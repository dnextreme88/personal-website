<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ config('app.name', 'Kevin\'s Portfolio') }}</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('images/logo.ico') }}" />

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- Styles --}}
        @filamentStyles
        @livewireStyles
        @vite('resources/css/app.css')

        @stack('styles')
        @stack('scripts')
    </head>

    <body x-data="window.darkModeSwitcher()" x-init="init" x-bind:class="{ 'dark': switchOn }" class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            {{-- Page Navigation --}}
            @if (isset($nav_menu))
                <div class="h-8 bg-gray-100 dark:bg-gray-600">&nbsp;</div>

                <nav class="bg-gray-100 dark:bg-gray-600 top-0 sticky z-[1]">{{ $nav_menu }}</nav>
            @endif

            {{-- Page Heading --}}
            @if (isset($header))
                <header class="bg-gray-300 shadow dark:bg-gray-500">
                    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">{{ $header }}</div>
                </header>
            @endif

            {{-- Page Content --}}
            <main>{{ $slot }}</main>

            @if (isset($footer))
                <footer class="mt-8 sm:mt-16">{{ $footer }}</footer>
            @endif
        </div>

        {{-- Scripts --}}
        @filamentScripts
        @livewireScripts
        @vite('resources/js/app.js')

        @stack('modals')
        <x-toaster-hub />
    </body>
</html>
