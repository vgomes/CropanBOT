const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

// elixir(mix => {
//     mix.sass('app.scss')
//        .webpack('app.js');
// });

elixir(function (mix) {
    mix.styles([
        'vendor/bootstrap/dist/css/bootstrap.css',
        'vendor/select2/dist/css/select2.min.css',
        'css/cropan.css'
    ], 'public/css/app.css', 'resources/assets');

    mix.scripts([
        'vendor/jquery/dist/jquery.min.js',
        'vendor/bootstrap/dist/js/bootstrap.min.js',
        'vendor/select2/dist/js/select2.min.js'
    ], 'public/js/app.js', 'resources/assets');

    mix.copy('resources/assets/vendor/bootstrap/fonts/', 'public/fonts/');
    mix.copy('resources/assets/img/', 'public/img/');
});