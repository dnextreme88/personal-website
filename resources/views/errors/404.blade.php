<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>404 — Signal Lost | {{ config('app.name', 'Kevin\'s Portfolio') }}</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('images/logo.ico') }}" />

        {{-- Apply the saved theme before paint (same key the dark-mode switcher
             uses) so this standalone page matches the rest of the site. --}}
        <script>
            try {
                if (JSON.parse(localStorage.getItem('kevinPortfolioIsDarkMode'))) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        </script>

        {{-- Fonts (mirrors the app layout) --}}
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Chakra+Petch:wght@400;500;600;700&family=Exo+2:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet" />

        @vite('resources/css/app.css')
    </head>

    <body class="font-text antialiased">
        <main class="relative flex flex-col items-center justify-center min-h-screen gap-6 px-6 overflow-hidden text-center bg-gray-100 dark:bg-gray-900">
            {{-- Drifting Tron grid backdrop --}}
            <div aria-hidden="true" class="absolute inset-0 opacity-40 cyber-grid animate-grid-drift"></div>

            <div class="relative flex flex-col items-center gap-6">
                <h1
                    class="glitch text-glow font-heading text-8xl sm:text-9xl text-gray-800 dark:text-gray-100"
                    data-text="404"
                >404</h1>

                <h2 class="text-3xl text-glow-magenta font-heading sm:text-4xl text-neon-magenta">Signal Lost</h2>

                <p class="max-w-md font-subtext text-gray-600 dark:text-gray-300">
                    The page you're looking for has dropped off the grid. It may have been moved,
                    deleted, or never existed in this timeline.
                </p>

                <x-button-call-to-action
                    :href="route('home')"
                    variant="magenta"
                    surface_class="bg-gradient-blue animate-shimmering-gradient bg-size-[800%_800%]"
                    aria-label="Back to homepage"
                >
                    Back to Homepage
                </x-button-call-to-action>
            </div>

            {{-- CRT scanline texture --}}
            <x-scanline-overlay />
        </main>
    </body>
</html>
