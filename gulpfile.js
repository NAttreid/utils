var gulp = require('gulp'),
        less = require('gulp-less'),
        sass = require('gulp-sass'),
        minify = require('gulp-clean-css'),
        concat = require('gulp-concat'),
        uglify = require('gulp-uglify'),
        rename = require('gulp-rename');

var paths = {
    'dev': {
        'js': './resources/assets/js/',
        'vendor': './resources/assets/vendor/'
    },
    'production': {
        'js': './assets/'
    }
};

var files = [
    paths.dev.js + 'MD5Hasher.js',
    paths.dev.js + 'RemoveDiacritics.js',
    paths.dev.js + 'jQuery.js',
    paths.dev.js + 'String.js',
    paths.dev.js + 'Number.js'
];

gulp.task('concat', function () {
    return gulp.src(files)
            .pipe(concat('utils.js'))
            .pipe(gulp.dest(paths.production.js));
});

gulp.task('minify', function () {
    return gulp.src(files)
            .pipe(concat('utils.min.js'))
            .pipe(uglify())
            .pipe(gulp.dest(paths.production.js));
});

gulp.task('watch', function () {
    gulp.watch(paths.dev.js + '/*.js', ['concat', 'minify']);
});

gulp.task('default', ['concat', 'minify', 'watch']); 