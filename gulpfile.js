var elixir = require('laravel-elixir');
var gulp = require('gulp');
var fs = require('fs');
var browserify = require('gulp-browserify');

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
    mix.browserify('dashboard/dashboard.js', 'public/js/dashboard.js');

    //Version the assets
    mix.version(['js/all.js', 'css/all.css']);

});


gulp.task('sw', function() {

    console.log("Building Service Worker JS");

    gulp.src('resources/assets/js/dashboard/service-worker.js')
        .pipe(browserify({
            insertGlobals : true
        }))
        .pipe(gulp.dest('public'));

    //Service Worker
    //mix.browserify('dashboard/service-worker.js', 'public/service-worker.js');

    console.log("Built Service Worker JS");
});
