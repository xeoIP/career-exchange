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

mix.sass('resources/assets/sass/app.scss', 'public/css');

mix.options({ processCssUrls: false });

/* Combine CSS */
mix.combine([
	'public/css/app.css',
	'public/assets/bootstrap/css/bootstrap.min.css',
	'public/assets/plugins/select2/css/select2.min.css',
	'public/assets/css/responsive.css',
	'public/assets/css/style.css',
	'public/assets/css/skins/skin-green.css',
	'public/assets/css/base.css',
	'public/assets/plugins/owlcarousel/assets/owl.carousel.min.css',
	'public/assets/plugins/owlcarousel/assets/owl.theme.default.min.css',
	'public/assets/css/flags/flags.min.css'
], 'public/css/app.css');

/* Combine JS */
mix.combine([
	'public/assets/js/jquery/2.1.3/jquery-2.1.3.min.js',
	'public/assets/js/scripts/custom.js',
	'public/assets/js/scripts/jquery.superfish.js',
	'public/assets/js/scripts/jquery.jpanelmenu.js',
	'public/assets/js/scripts/stacktable.js',
	'public/assets/js/scripts/headroom.min.js',
	'public/assets/js/scripts/jquery.themepunch.showbizpro.min.js',
	'public/assets/js/scripts/jquery.themepunch.revolution.min.js',
	'public/assets/js/scripts/jquery.flexslider-min.js',
	'public/assets/js/scripts/jquery.themepunch.tools.min.js',
	'public/assets/js/script.js',
	'public/assets/js/scripts/chosen.jquery.min.js',
	'public/assets/js/scripts/jquery.magnific-popup.min.js',
	'public/assets/js/scripts/waypoints.min.js',
	'public/assets/js/scripts/jquery.counterup.min.js',
	'public/assets/plugins/SocialShare/SocialShare.min.js',
	'public/assets/plugins/owlcarousel/owl.carousel.js',
	'public/assets/bootstrap/js/bootstrap.min.js',
	'public/assets/js/jquery.matchHeight-min.js',
	'public/assets/plugins/jquery.fs.scroller/jquery.fs.scroller.min.js',
	'public/assets/plugins/select2/js/select2.full.min.js',
	'public/assets/plugins/SocialShare/SocialShare.min.js',
	'public/assets/js/hideMaxListItem-min.js',
	'public/assets/plugins/autocomplete/jquery.mockjax.js',
	'public/assets/plugins/autocomplete/jquery.autocomplete.min.js',
	'public/assets/js/app/autocomplete.cities.js',
	'public/assets/js/form-validation.js',
	'public/assets/js/app/show.phone.js',
	'public/assets/js/app/make.favorite.js'
], 'public/js/app.js');

/* Minify assets */
mix.minify('public/css/app.css');
mix.minify('public/js/app.js');

/* Cache busting */
mix.version();
