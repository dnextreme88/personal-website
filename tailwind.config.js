import preset from './vendor/filament/support/tailwind.config.preset'
import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    darkMode: 'class',
    content: [
        './app/Filament/**/*.php',
        './resources/views/*.blade.php',
        './resources/**/*.js',
        './storage/framework/views/*.php',
        './vendor/filament/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['Lori', ...defaultTheme.fontFamily.serif],
            },
        },
    },
    plugins: [
        require('tailwindcss-intersect'),
    ],
};
