const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#4f46e5',
                    light: '#6366f1',
                    dark: '#4338ca',
                    '50': '#eef2ff',
                    '100': '#e0e7ff',
                    '200': '#c7d2fe',
                    '300': '#a5b4fc',
                    '400': '#818cf8',
                    '500': '#6366f1',
                    '600': '#4f46e5',
                    '700': '#4338ca',
                    '800': '#3730a3',
                    '900': '#312e81',
                },
                secondary: {
                    DEFAULT: '#10b981',
                    light: '#34d399',
                    dark: '#059669',
                },
                success: colors.green,
                danger: colors.rose,
                warning: colors.amber,
                info: colors.blue,
                dark: {
                    bg: '#111827',
                    'bg-secondary': '#1f2937',
                    'text-primary': '#f9fafb',
                    'text-secondary': '#e5e7eb',
                },
            },
            boxShadow: {
                'card': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                'card-hover': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            },
            animation: {
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'slide-up': {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],

    variants: {
        extend: {
            opacity: ['disabled'],
            backgroundColor: ['active', 'disabled', 'dark'],
            textColor: ['active', 'disabled', 'dark'],
            borderColor: ['active', 'disabled', 'dark'],
            ringColor: ['hover', 'active', 'focus', 'dark'],
            ringOffsetColor: ['hover', 'active', 'focus', 'dark'],
            ringOffsetWidth: ['hover', 'active', 'focus'],
            ringOpacity: ['hover', 'active', 'focus', 'dark'],
            ringWidth: ['hover', 'active', 'focus'],
        },
    },
};
