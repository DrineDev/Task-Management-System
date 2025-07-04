import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './resources/**/*.css',
    ],

    theme: {
        extend: {
            fontFamily: {
            istok: ['"Istok Web"', 'sans-serif'],
            inter: ['Inter', 'sans-serif'],
            },
            colors: {
                primary: '#C7B89B',
                secondary: '#2F2D2A',
                background: '#1E1E1E',
                text: '#F4EBD9',
            },
            colors: {
                primary: '#C7B89B',
                secondary: '#2F2D2A',
                background: '#1E1E1E',
                text: '#F4EBD9',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
    ],
};
