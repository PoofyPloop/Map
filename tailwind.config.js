import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    100: '#a4b8ef',
                    200: '#7795e8',
                    300: '#4971e0',
                    400: '#1c4ed8',
                    500: '#1946c2',
                    600: '#163ead',
                    700: '#143797',
                    800: '#112f82'
                }
            }
        },
    },

    plugins: [forms],
};
