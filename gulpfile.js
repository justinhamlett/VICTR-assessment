// define Gulp and preprocessors
var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    uglify = require('gulp-uglify'),
    watch = require('gulp-watch'),
    concat = require('gulp-concat'),
    jshint = require('gulp-jshint'),
    changed = require('gulp-changed'),
    imagemin = require('gulp-imagemin'),
    stripDebug = require('gulp-strip-debug'),
    autoprefix = require('gulp-autoprefixer'),
    clean = require('gulp-clean'),
    rename = require('gulp-rename'),
    cssmin = require('gulp-cssmin');

// directories
var src = './assets/src/',
    dest = './assets/';

// clean task
gulp.task('clean', function() {
    return gulp.src([dest + 'css/*', dest + 'js/*'], {read: false})
        .pipe(clean());
});

// scss task
gulp.task('scss', function() {
    return sass(src + 'scss/main.scss')
        .pipe(autoprefix('last 2 versions'))
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(dest + 'css/'));

});

// js task
gulp.task('js', function() {
    // main app js file
    gulp.src(src + 'js/app.js')
        .pipe(stripDebug())
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(dest + 'js/'));
});

// bower components
gulp.task('bower', function() {

    // bower js components
    gulp.src([
        src + 'vendor/bootstrap/dist/js/bootstrap.min.js',
        src + 'vendor/jquery/dist/jquery.min.js',
        src + 'vendor/tether/dist/js/tether.min.js'
    ])
        .pipe(gulp.dest(dest + 'js'));

    // bower css components
    gulp.src([src + 'vendor/bootstrap/dist/css/bootstrap.min.css'])
        .pipe(gulp.dest(dest + 'css'));
});

// jshint task
gulp.task('jshint', function() {
    gulp.src(src + 'js/app.js')
        .pipe(jshint())
        .pipe(jshint.reporter('jshint-stylish'));
});

// imagemin task
gulp.task('imagemin', function() {
    var imgSrc = src + 'images/**/*.png',
        imgDst = dest + 'images';

    gulp.src(imgSrc)
        .pipe(changed(imgDst))
        .pipe(imagemin())
        .pipe(gulp.dest(imgDst));
});

// Default Gulp run task
gulp.task('default', ['clean', 'scss', 'js', 'imagemin', 'bower']);

// watch task
gulp.task('watch', function() {
    // watch Sass files
    gulp.watch(src + 'scss/**/*.scss', ['scss']);
    // watch JavaScript files
    gulp.watch(src + 'js/app.js', ['js']);
    // watch image files
    gulp.watch(src + 'images/**/*.png', ['imagemin']);
});
