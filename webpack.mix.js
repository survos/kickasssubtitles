let mix = require('laravel-mix');
let WebpackShellPlugin = require('webpack-shell-plugin');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    plugins:[
        new WebpackShellPlugin({
            onBuildStart:['php artisan lang:js --quiet --no-lib'],
            onBuildEnd:[]
        })
    ]
});

mix
    .js('resources/js/app.js', 'public/js')
    .sass('resources/scss/app.scss', 'public/css')
;

if (mix.inProduction()) {
    mix.version();
}
