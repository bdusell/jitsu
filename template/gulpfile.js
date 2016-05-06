var gulp = require('gulp');
var _ = require('lodash');

var builds = {
  'dev': {
    minifyCss: false,
    minifyJs: false,
    cssSourceMaps: true,
    jsSourceMaps: true
  },
  'prod': {
    minifyCss: true,
    minifyJs: true,
    cssSourceMaps: false,
    jsSourceMaps: false
  }
};

var symlink = function(src, dest) {
  return function() {
    var gulpPlumber = require('gulp-plumber');
    var gulpSymlink = require('gulp-symlink');
    return gulp.src(src)
      .pipe(gulpPlumber())
      .pipe(gulpSymlink(dest, {
        force: true,
        log: false
      }));
  };
};

var runCommand = function(name, args) {
  var child_process = require('child_process');
  return child_process.spawn(name, args, {
    stdio: ['ignore', 'pipe', 2]
  }).stdout;
};

var configTemplate = function(name, finName, foutName) {
  return function() {
    var Q = require('q');
    return Q.Promise(function(resolve, reject) {
      var fs = require('fs');
      var path = require('path');
      var mkdirp = require('mkdirp');
      mkdirp(path.dirname(foutName), function(err) {
        if(err) {
          reject(err);
        } else {
          runCommand('./src/php/vendor/bin/jitsu-config-template', [
            '-c', './src/php/config.php',
            '-c', './src/etc/config.' + name + '.php',
            finName
          ]).pipe(fs.createWriteStream(foutName))
          .on('finish', resolve)
          .on('error', reject);
        }
      });
    });
  };
};

_.each(builds, function(build, name) {

  gulp.task('build:' + name, [
    'build:symlink:index:' + name,
    'build:symlink:config:' + name,
    'build:symlink:php:' + name,
    'build:symlink:assets:' + name,
    'build:config:htaccess:' + name,
    'build:config:robots:' + name,
    'build:css:' + name,
    'build:js:' + name
  ]);

  gulp.task('watch:' + name, [
    'watch:config:htaccess:' + name,
    'watch:config:robots:' + name,
    'watch:css:' + name,
    'watch:js:' + name
  ]);

  gulp.task('build:symlink:index:' + name, symlink(
    'src/etc/index.' + name + '.php',
    'build/' + name + '/index.php'
  ));

  gulp.task('build:symlink:config:' + name, symlink(
    'src/etc/config.' + name + '.php',
    'build/' + name + '/config.php'
  ));

  gulp.task('build:symlink:php:' + name, symlink(
    'src/php',
    'build/' + name + '/app'
  ));

  gulp.task('build:symlink:assets:' + name, symlink(
    'assets',
    'build/' + name + '/assets'
  ));

  gulp.task('build:config:htaccess:' + name, configTemplate(name,
    './src/etc/htaccess.php',
    './build/' + name + '/.htaccess'
  ));

  gulp.task('watch:config:htaccess:' + name, function() {
    gulp.watch([
      'src/php/config.php',
      'src/etc/config.' + name + '.php',
      'src/etc/htaccess.php'
    ], ['build:config:htaccess:' + name]);
  });

  gulp.task('build:config:robots:' + name, configTemplate(name,
    './src/etc/robots.txt.php',
    './build/' + name + '/robots.txt'
  ));

  gulp.task('watch:config:robots:' + name, function() {
    gulp.watch([
      'src/php/config.php',
      'src/etc/config.' + name + '.php',
      'src/etc/robots.txt.php'
    ], ['build:config:robots:' + name]);
  });

  gulp.task('build:css:' + name, function() {
    var gulpSass = require('gulp-sass');
    var gulpAutoprefixer = require('gulp-autoprefixer');
    var gulpRename = require('gulp-rename');
    var gulpPlumber = require('gulp-plumber');
    var r;
    r = gulp.src('src/css/main.scss')
      .pipe(gulpPlumber());
    var gulpSourcemaps;
    if(build.cssSourceMaps) {
      gulpSourcemaps = require('gulp-sourcemaps');
      r = r.pipe(gulpSourcemaps.init({ debug: true }));
    }
    r = r.pipe(gulpSass())
      .pipe(gulpRename('main.css'))
      .pipe(gulpAutoprefixer());
    if(build.minifyCss) {
      var gulpMinifyCss = require('gulp-minify-css');
      r = r.pipe(gulpMinifyCss());
    }
    if(build.cssSourceMaps) {
      r = r.pipe(gulpSourcemaps.write());
    }
    return r.pipe(gulp.dest('build/' + name + '/'));
  });

  gulp.task('watch:css:' + name, function() {
    gulp.watch('src/css/**/*.scss', ['build:css:' + name]);
  });

  gulp.task('build:js:' + name, function() {
    var browserify = require('browserify');
    var vinylSourceStream = require('vinyl-source-stream');
    var gulpPlumber = require('gulp-plumber');
    var r;
    r = browserify('./src/js/main.js', {
      debug: build.jsSourceMaps
    }).bundle()
      .pipe(vinylSourceStream('main.js'))
      .pipe(gulpPlumber());
    if(build.minifyJs) {
      var vinylBuffer = require('vinyl-buffer');
      var gulpUglify = require('gulp-uglify');
      var gulpSourcemaps;
      r = r.pipe(vinylBuffer());
      if(build.jsSourceMaps) {
        gulpSourcemaps = require('gulp-sourcemaps');
        r = r.pipe(gulpSourcemaps.init({ loadMaps: true }));
      }
      r = r.pipe(gulpUglify());
      if(build.jsSourceMaps) {
        r = r.pipe(gulpSourcemaps.write());
      }
    }
    return r.pipe(gulp.dest('build/' + name + '/'));
  });

  gulp.task('watch:js:' + name, function() {
    gulp.watch('src/js/**/*.js', ['build:js:' + name]);
  });

  gulp.task('clean:' + name, function(cb) {
    var del = require('del');
    del([
      'build/' + name + '/'
    ], cb);
  });
});

gulp.task('build', _.map(builds, function(build, name) { return 'build:' + name; }));

gulp.task('clean', function(cb) {
  var del = require('del');
  del([
    'build/'
  ], cb);
});

gulp.task('default', ['build:dev', 'watch:dev']);
