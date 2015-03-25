var gulp        = require('gulp'),
    uglify      = require('gulp-uglify'),
    jshint      = require('gulp-jshint'),
    concat      = require('gulp-concat'),
    less        = require('gulp-less'),
    autoprefix  = require('gulp-autoprefixer'),
    sourcemaps  = require('gulp-sourcemaps'),
    order       = require('gulp-order'),
    cssmin      = require('gulp-cssmin');

// Compile all asset files
gulp.task('build', ['js', 'less']);

// Compile JavaScript files
gulp.task('js', function () {

    gulp.src('assets/js/*.js')
        .pipe(order([
            'app.js'
        ]))
        .pipe(jshint())
        .pipe(jshint.reporter('default'))
        .pipe(sourcemaps.init())
        .pipe(uglify())
        .pipe(concat('compiled.js'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('dist/js'));

});

// Compile JavaScript libraries
gulp.task('libraries', function () {

    gulp.src('assets/js/vendor/**/*.js')
        .pipe(order([
            'jquery.js'
        ]))
        .pipe(uglify({mangle: false}))
        .pipe(concat('libraries.js'))
        .pipe(gulp.dest('dist/js'));

});

// Compile LESS files
gulp.task('less', function () {

    gulp.src('assets/less/app.less')
        .pipe(sourcemaps.init())
        .pipe(less())
        .pipe(autoprefix('last 2 version', 'ie 8', 'ie 9'))
        .pipe(sourcemaps.write())
        .pipe(cssmin())
        .pipe(gulp.dest('dist/css'));

});

// Watcher to watch for changes to assets and compile them
gulp.task('watch', function () {

    gulp.watch('assets/js/**/*.js', ['js']);
    gulp.watch('assets/js/vendor/**/*.js', ['libraries']);
    gulp.watch('assets/less/**/*.less', ['less']);

});