const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            screens: {
                '8xl': '1800px',
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
