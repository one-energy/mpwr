const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            maxWidth: {
                '8xl': '110rem',
            },
            screens: {
                'headerLimit': '1163px',
                // => @media (min-width: 640px) { ... }
            },
            colors: {
                'gray-base': '#F1F1F1'
            }
        },
    },
    plugins: [
        require('@tailwindcss/ui'),
    ],
    variants: {
        borderStyle: ['responsive', 'last'],
    },
};
