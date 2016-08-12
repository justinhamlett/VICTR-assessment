var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    uglify = require('gulp-uglify'),
    watch = require('gulp-watch'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    jshint = require('gulp-jshint'),
    changed = require('gulp-changed'),
    imagemin = require('gulp-imagemin'),
    stripDebug = require('gulp-strip-debug'),
    autoprefix = require('gulp-autoprefixer'),
    clean = require('gulp-clean'),
    rename = require('gulp-rename'),
    cssmin = require('gulp-cssmin');

// clean task
gulp.task('clean', function() {
    return gulp.src(['./assets/css/*', './assets/js/*'], {read: false})
        .pipe(clean());
});

// scss task
gulp.task('scss', function() {
    return sass('./assets/src/scss/main.scss')
        .pipe(autoprefix('last 2 versions'))
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./assets/css/'));

});

// js task
gulp.task('js', function() {
    // main app js file
    gulp.src('./assets/src/js/app.js')
        .pipe(stripDebug())
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('./assets/js/'));
});

// bower components
gulp.task('bower', function() {

    // bower js components
    gulp.src([
        './assets/src/vendor/bootstrap/dist/js/bootstrap.min.js',
        './assets/src/vendor/jquery/dist/jquery.min.js',
        './assets/src/vendor/tether/dist/js/tether.min.js'
    ])
        .pipe(gulp.dest('./assets/js'));

    // bower css components
    gulp.src(['./assets/src/vendor/bootstrap/dist/css/bootstrap.min.css'])
        .pipe(gulp.dest('./assets/css'));
});

// jshint task
gulp.task('jshint', function() {
    gulp.src('./assets/src/js/**/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('jshint-stylish'));
});

// imagemin task
gulp.task('imagemin', function() {
    var imgSrc = './assets/src/images/**/*.png',
        imgDst = './assets/images';

    gulp.src(imgSrc)
        .pipe(changed(imgDst))
        .pipe(imagemin())
        .pipe(gulp.dest(imgDst));
});

// // copy html task
// gulp.task('copyHtml', function() {
//     gulp.src('./src/*.html')
//         .pipe(gulp.dest('./dist'));
// });

// Default Gulp run task
gulp.task('default', ['clean', 'scss', 'js', 'imagemin', 'bower']);

// watch task
gulp.task('watch', function() {
    // watch scss files
    gulp.watch('./assets/src/scss/**/*.scss', ['scss']);

    gulp.watch('./assets/src/js/**/*.js', ['js']);

    gulp.watch('./assets/src/images/**/*.png', ['imagemin']);
});
