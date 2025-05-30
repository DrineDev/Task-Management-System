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
            },
        },
    },

    extend: {
  colors: {
    threadGold: '#fcd34d',
    emberRose: '#fb7185',
    fatePink: '#f9a8d4',
    ashBlack: '#0a0a0a',
    parchmentIvory: '#fef9f5',
    sunbeam: '#fde68a',
    lightGlow: '#fffacc',
  },
  fontFamily: {
    display: ['"Playfair Display"', 'serif'],
    body: ['"Inter"', 'sans-serif'],
    lore: ['"EB Garamond"', 'serif'],
    regal: ['"Cormorant Garamond"', 'serif'],
  },
},


    plugins: [forms],
};
