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

    //General app css
    mix.sass('app.scss', 'resources/assets/css/build/app.css')
        .styles([
            'resources/assets/css/build/app.css',
            'resources/assets/css/vendor/glyphicon.css'
        ]);

    //Dashboard CSS
    mix.sass('dashboard.scss', 'resources/assets/css/build/dashboard.css')
        .styles([
            'resources/assets/css/build/app.css',   //Include the build step from above
            'resources/assets/css/build/dashboard.css',
            'resources/assets/css/vendor/glyphicon.css'
        ], 'public/css/dashboard.css');

    //Main JS
    mix.scripts([
            'node_modules/jquery/dist/jquery.js',
            'node_modules/bootstrap-sass/assets/javascripts/bootstrap.js',
            'resources/assets/js/vendor/*.js',
            'resources/assets/js/*.js'
        ],
        'public/js/all.js',
        './');

    //Dashboard JS
    mix.scripts([
            'node_modules/jquery/dist/jquery.js',
            'node_modules/bootstrap-sass/assets/javascripts/bootstrap.js',
            'node_modules/vue/dist/vue.js',
            'node_modules/vue-resource/dist/vue-resource.js',
            'resources/assets/js/vendor/*.js',
            'resources/assets/js/dashboard/components/*.js',
            'resources/assets/js/dashboard/*.js',
            'resources/assets/js/*.js'
        ],
        'public/js/dashboard.js',
        './');

    mix.version(['js/all.js', 'js/dashboard.js', 'css/all.css', 'css/dashboard.css']);
});
