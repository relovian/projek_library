import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                'bawaslu-red': 'var(--bawaslu-red)',
                'bawaslu-dark-red': 'var(--bawaslu-dark-red)',
                'bawaslu-gold': 'var(--bawaslu-gold)', 
                'bg': 'var(--bg)',
                'surface': 'var(--surface)',
                'surface2': 'var(--surface2)',
                'hitam': 'var(--text)',
                'abu': 'var(--text-abu)',
                'border': 'var(--border)',
            }
        },
    },

    plugins: [forms],
};
