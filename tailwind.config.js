import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './app/Filament/**/*.php',
        './resources/views/*.blade.php',
        './resources/**/*.js',
        './storage/framework/views/*.php',
        './vendor/filament/**/*.blade.php',
        './vendor/masmerise/livewire-toaster/resources/views/*.blade.php',
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
