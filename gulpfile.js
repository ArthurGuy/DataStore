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
        ]);

    mix.scripts([
            'node_modules/jquery/dist/jquery.js',
            'node_modules/bootstrap-sass/assets/javascripts/bootstrap.js',
            'node_modules/vue/dist/vue.js',
            'node_modules/vue-resource/dist/vue-resource.js',
            'resources/assets/js/vendor/dygraph-combined.js',
            'resources/assets/js/vendor/ICanHaz.min.js',
            'resources/assets/js/vendor/skycons.js'
        ],
        'public/js/app.js',
        './');

    mix.version(['js/app.js', 'css/all.css']);
});
