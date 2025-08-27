import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    darkMode: 'class', // ← автоматическая тёмная тема на основе ОС
    theme: {
        extend: {
            fontFamily: {
                futura: ['"Futura PT"', ...defaultTheme.fontFamily.sans],
                sans: ['Futura PT', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                green: {
                    400: '#84C9BA',
                    500: '#47af98',
                    600: '#267868'
                },
                dark_bg: '#1c1c16',
                dark: {
                    100: '#E0E0E0',
                    200: '#BDBDBD',
                    400: '#4c4b46',
                    500: '#363531',
                    600: '#1c1c16'
                },
                brown: {
                    300: '#ECBA57'
                },
                blue: {
                    500: '#66a2e5'
                }
            }, screens: {
                '3xl': {'max': '1920px'},
                '2xl': {'max': '1535px'}, // => @media (max-width: 1535px) { ... }
                'xl': {'max': '1279px'}, // => @media (max-width: 1279px) { ... }
                'lg': {'max': '1023px'}, // => @media (max-width: 1023px) { ... }
                'md': {'max': '767px'}, // => @media (max-width: 767px) { ... }
                'sm': {'max': '639px'}, // => @media (max-width: 639px) { ... }
            }
        },
    },
};
