let mix = require('laravel-mix');

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
// not complie this each time
mix.js('resources/assets/js/app.js', 'public/js');//.sass('resources/assets/sass/app.scss', 'public/css');
mix.styles([
	'public/css/bootstrap.min.css',
	'public/css/front.css',
	'public/css/admin.css'
], 	'public/css/all.css')
.scripts([
	//'resources/assets/js/jquery-1.12.4.min.js',
    'public/js/app.js',
    'public/js/front.js',
    'public/js/admin.js'
], 	'public/js/all.js').version();

// if (mix.inProduction()) {
//     mix.version();
// }
