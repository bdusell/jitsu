var gulp = require('gulp');

var del = require('del');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');
var plumber = require('gulp-plumber');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');

var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var minifyCss = require('gulp-minify-css');

var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');
var browserify = require('browserify');

var paths = {
  scripts: 'src/js/**/*.js',
  stylesheets: 'src/css/**/*.scss',
  mainScript: './src/js/main.js',
  mainStylesheet: 'src/css/main.scss'
};

gulp.task('clean', function(cb) {
  del([
    'build/dev/css',
    'build/dev/js',
    'build/prod/css',
    'build/prod/js'
  ], cb);
});

gulp.task('css', function() {
  return gulp.src(paths.mainStylesheet)
    .pipe(plumber())
    .pipe(sourcemaps.init())
      .pipe(sass())
      .pipe(autoprefixer())
    .pipe(sourcemaps.write())
    .pipe(rename('main.css'))
    .pipe(gulp.dest('build/dev/css'))
    .pipe(minifyCss())
    .pipe(gulp.dest('build/prod/css'));
});

gulp.task('lint', function() {
  return gulp.src(paths.scripts)
    .pipe(plumber())
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});

gulp.task('js', function() {
  return browserify({
    entries: [paths.mainScript],
    debug: true
  }).bundle()
    .pipe(plumber())
    .pipe(source('main.js'))
    .pipe(gulp.dest('build/dev/js'))
    .pipe(buffer())
    .pipe(uglify({ preserveComments: 'some' }))
    .pipe(gulp.dest('build/prod/js'))
});

gulp.task('watch', function() {
  gulp.watch(paths.stylesheets, ['css']);
  gulp.watch(paths.scripts, ['lint', 'js']);
});

gulp.task('build', ['css', 'lint', 'js']);
gulp.task('default', ['build', 'watch']);
