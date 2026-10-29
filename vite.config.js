import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filament/admin/tailwind-theme.css',
                'resources/js/app.js',
                'resources/js/dropping-areas-route.js',
                'resources/js/game-screenshots-gallery.js',
                'resources/js/skeleton-loading.js',
                'resources/js/sold-items-chart.js',
            ],
            refresh: true,
        }),
    ],
});
