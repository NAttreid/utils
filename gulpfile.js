var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify');

var paths = {
    'dev': {
        'js': './resources/assets/js/',
        'vendor': './node_modules/'
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
    paths.dev.js + 'Number.js',
    paths.dev.js + 'Cookie.js'
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

gulp.task('boundled', function () {
    var boundled = files;
    boundled.unshift(paths.dev.vendor + 'jquery/dist/jquery.js');
    return gulp.src(boundled)
        .pipe(concat('utils.boundled.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(paths.production.js));
});

gulp.task('watch', function () {
    gulp.watch(paths.dev.js + '/*.js', gulp.series('concat', 'minify', 'boundled'));
});

gulp.task('default', gulp.series('concat', 'minify', 'boundled', 'watch'));