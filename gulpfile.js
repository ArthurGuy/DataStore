var elixir = require('laravel-elixir');
var gulp = require('gulp');
var fs = require('fs');
var browserify = require('gulp-browserify');
var bump = require('gulp-bump');
var runSequence = require('run-sequence');



gulp.task('bump-dashboard-version', function() {

    return gulp.src('resources/assets/versions.json')
        .pipe(bump({key: "dashboard"}))
        .pipe(gulp.dest('resources/assets/'));

});

gulp.task('generate-versions-file', ['bump-dashboard-version'], function() {

    //Generate a versions module for use in the individual js files
    var obj = JSON.parse(fs.readFileSync('resources/assets/versions.json', 'utf8'));
    fs.writeFile("resources/assets/js/versions.js", "module.exports = {'dashboard':'"+obj['dashboard']+"'}");

});



elixir(function(mix) {



    runSequence('generate-versions-file');


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
            'resources/assets/js/vendor/*.js'
            //'resources/assets/js/*.js'
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


    runSequence('generate-versions-file');


    gulp.src('resources/assets/js/dashboard/service-worker.js')
        .pipe(browserify({
            insertGlobals : true
        }))
        .pipe(gulp.dest('public'));


    console.log("Built Service Worker JS");

});


