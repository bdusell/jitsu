var gulp = require('gulp');

var del = require('del');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');

var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var minifyCss = require('gulp-minify-css');

var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');

var paths = {
  scripts: 'src/js/**/*.js',
  stylesheets: 'src/css/**/*.scss'
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
  gulp.src('src/css/main.scss')
    .pipe(sourcemaps.init())
      .pipe(sass())
      .pipe(autoprefixer())
    .pipe(sourcemaps.write())
    .pipe(rename('main.css'))
    .pipe(gulp.dest('build/dev/css'))
    .pipe(minifyCss())
    .pipe(rename('main.min.css'))
    .pipe(gulp.dest('build/prod/css'));
});

gulp.task('lint', function() {
  return gulp.src(paths.scripts)
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});

gulp.task('js', function() {
  return gulp.src(paths.scripts)
    .pipe(concat('main.js'))
    .pipe(gulp.dest('build/dev/js'))
    .pipe(uglify())
    .pipe(rename('main.min.js'))
    .pipe(gulp.dest('build/prod/js'))
});

gulp.task('watch', function() {
  gulp.watch(paths.stylesheets, ['css']);
  gulp.watch(paths.scripts, ['lint', 'js']);
});

gulp.task('default', ['css', 'lint', 'js', 'watch']);
