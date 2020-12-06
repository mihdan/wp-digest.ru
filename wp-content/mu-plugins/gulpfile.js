'use strict';

const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const rename = require("gulp-rename");
const sourcemaps = require('gulp-sourcemaps');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify-es').default;
const livereload = require('gulp-livereload');

const files = {
    scss: {
        src: ['assets/scss/app.scss'],
        watch: ['assets/scss/**/*.scss', 'assets/scss/app.scss']
    },
    gutenberg_scss: {
        src: ['assets/scss/gutenberg.scss'],
        watch: [ 'assets/scss/**/*.scss', 'assets/scss/gutenberg.scss']
    },
    //js: {
    //    src: ['assets/js/lib/*.js', 'assets/js/app.js'],
    //    watch: ['assets/js/lib/*.js', 'assets/js/app.js']
    //},
    html: {
        src: ['*.php'],
        watch: ['*.php']
    }
};

function stylesTask() {
    return src(files.scss.src)
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(rename('assets/css/app.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(dest('.'))
        .pipe(livereload());
}

function gutenbergStylesTask() { console.log(files.gutenberg_scss.src);
    return src(files.gutenberg_scss.src)
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(rename('assets/css/gutenberg.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(dest('.'))
        .pipe(livereload());
}

// function jsTask() {
//     return src(files.js.src)
//         .pipe(concat('app.min.js'))
//         .pipe(uglify())
//         .pipe(dest('assets/js'))
//         .pipe(livereload());
// }

function htmlTask() {
    return src(files.html.src)
        .pipe(livereload());
}

function watchTask() {
    livereload.listen();
    watch(files.scss.watch, parallel(stylesTask));
    watch(files.gutenberg_scss.watch, parallel(gutenbergStylesTask));
//    watch(files.js.watch, parallel(jsTask));
    watch(files.html.watch, parallel(htmlTask));
}

exports.styles = series(
    stylesTask
);

// exports.js = series(
//     jsTask
// );

exports.html = series(
    htmlTask
);

exports.watch = series(
    watchTask
);

exports.default = series(
    stylesTask,
    gutenbergStylesTask,
//    jsTask,
    htmlTask
);
