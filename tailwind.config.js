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
