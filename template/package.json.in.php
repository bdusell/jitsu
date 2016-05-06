{
  "name": <?= json_encode($package_name) ?>,
  "version": "0.1.0",
  "description": <?= json_encode($project_name) ?>,
  "main": "gulpfile.js",
  "author": <?= json_encode(get_current_user()) ?>,
  "license": "ISC",
  "devDependencies": {
    "browserify": "^13.0.0",
    "del": "^2.2.0",
    "gulp": "^3.9.1",
    "gulp-autoprefixer": "^3.1.0",
    "gulp-minify-css": "^1.2.4",
    "gulp-plumber": "^1.1.0",
    "gulp-rename": "^1.2.2",
    "gulp-sass": "^2.3.1",
    "gulp-sourcemaps": "^2.0.0-alpha",
    "gulp-symlink": "^2.1.4",
    "gulp-uglify": "^1.5.3",
    "lodash": "^4.11.2",
    "mkdirp": "^0.5.1",
    "q": "^1.4.1",
    "vinyl-buffer": "^1.0.0",
    "vinyl-source-stream": "^1.1.0"
  }
}
