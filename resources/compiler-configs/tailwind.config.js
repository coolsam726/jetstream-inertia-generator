const defaultTheme = require('tailwindcss/defaultTheme');
const colors  = require("tailwindcss/colors");
module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                info: {...colors.blue, DEFAULT:colors.blue["400"]},
                primary: {...colors.indigo, DEFAULT: colors.indigo[800]},
                secondary: {
                    DEFAULT: '#F99119',
                    '50': '#FDE4C7',
                    '100': '#FDD8AE',
                    '200': '#FBC17D',
                    '300': '#FAA94B',
                    '400': '#F99119',
                    '500': '#D97706',
                    '600': '#A75C05',
                    '700': '#764103',
                    '800': '#442502',
                    '900': '#120A01'
                },
                warning: {
                    ...colors.yellow,
                    DEFAULT: colors.yellow["500"]
                },
                danger: {
                    ...colors.rose,
                    DEFAULT: colors.rose["500"]
                },
                success: {
                    ...colors.green,
                    DEFAULT: colors.green["500"]
                },
            },
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
            width: ["responsive", "hover", "focus"],
            height: ["responsive", "hover", "focus"],
            objectFit: ["responsive", "hover", "focus"],
            borderRadius: ["hover","focus"]
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
