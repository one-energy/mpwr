const mix         = require('laravel-mix');
const tailwindcss = require('tailwindcss');
require('laravel-mix-purgecss');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss       : [tailwindcss('./tailwind.config.js')],
    })
    .version()
    .purgeCss({
        enabled: mix.inProduction(),
        defaultExtractor: content => content.match(/[\w-/.:]+(?<!:)/g) || []
    });
