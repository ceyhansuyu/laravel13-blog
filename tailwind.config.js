import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
            extend: {
                fontFamily: {
                    // Önce Inter'i, sonra Figtree'yi, en sona default'ları ekledik
                    sans: ['Inter', ...defaultTheme.fontFamily.sans],
                },
            },
        },

    plugins: [forms],
};
