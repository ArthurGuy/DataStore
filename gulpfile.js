var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss', 'resources/assets/css')
        .styles([
            'resources/assets/css/app.css',
            'resources/assets/css/glyphicon.css'
        ])
        .version('css/all.css');
});
