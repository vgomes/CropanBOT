var elixir = require('laravel-elixir');

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

elixir(function(mix) {
    mix.phpUnit();

    mix.styles([
        'vendor/bootstrap/dist/css/bootstrap.css'
    ], 'public/css/style.css', 'resources/assets');

    mix.scripts([
        'vendor/jquery/dist/jquery.min.js',
        'vendor/bootstrap/dist/js/bootstrap.js'
    ], 'public/js/script.js', 'resources/assets');
});
