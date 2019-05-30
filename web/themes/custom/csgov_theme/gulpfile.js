'use strict';

// Include gulp.
var gulp = require('gulp');
var config = require('./config.json');

// Include plugins.
var sass = require('gulp-sass');
var imagemin = require('gulp-imagemin');
var plumber = require('gulp-plumber');
var glob = require('gulp-sass-glob');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var notify = require('gulp-notify');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');
var jshint = require('gulp-jshint');
var postcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
var del = require('del');
var browserSync = require('browser-sync').create();

// Check if local config exists.
var fs = require('fs');
if (!fs.existsSync('./config_local.json')) {
  console.log('\x1b[33m', 'You need to rename default.config_local.json to' +
      ' config_local.json and update its content if necessary.', '\x1b[0m');
  process.exit();
}
//Include local config.
var configLocal = require('./config_local.json');

// Process CSS for production.
gulp.task('css', function() {
  var postcssPlugins = [
    autoprefixer('last 2 versions', '> 1%', 'ie 11')
  ];

  return gulp.src(config.css.src)
      .pipe(glob())
      .pipe(plumber({
        errorHandler: function (error) {
          notify.onError({
            title:    "Gulp",
            subtitle: "Failure!",
            message:  "Error: <%= error.message %>"
          }) (error);
          this.emit('end');
        }}))
      .pipe(sass({
        outputStyle: 'compressed',
        errLogToConsole: true
      }))
      .pipe(postcss(postcssPlugins))
      .pipe(gulp.dest(config.css.dest))
});

// Process CSS for development.
gulp.task('css_dev', function() {
  var postcssPlugins = [
    autoprefixer('last 2 versions', '> 1%', 'ie 11')
  ];

 return gulp.src(config.css.src)
      .pipe(glob())
      .pipe(plumber({
        errorHandler: function (error) {
          notify.onError({
            title:    "Gulp",
            subtitle: "Failure!",
            message:  "Error: <%= error.message %>"
          }) (error);
          this.emit('end');
        }}))
      .pipe(sourcemaps.init())
      .pipe(sass({
        outputStyle: 'nested',
        errLogToConsole: true
      }))
      .pipe(postcss(postcssPlugins))
      .pipe(sourcemaps.write('./'))
      .pipe(gulp.dest(config.css.dest))
      .pipe(browserSync.reload({ stream: true, match: '**/*.css' }));
});

// Compress images.
gulp.task('images', function () {
  return gulp.src(config.images.src)
      .pipe(imagemin([
        imagemin.gifsicle({interlaced: true}),
        imagemin.jpegtran({progressive: true}),
        imagemin.optipng({optimizationLevel: 5}),
        imagemin.svgo({
          plugins: [
            {removeViewBox: false},
            {cleanupIDs: false}
          ]
        })
      ]))
      .pipe(gulp.dest(config.images.dest));
});

// Concat all JS files into one file and minify it.
gulp.task('scripts', function() {
  return gulp.src(config.js.src)
      .pipe(plumber({
        errorHandler: function (error) {
          notify.onError({
            title: 'Gulp scripts processing',
            subtitle: 'Failure!',
            message: 'Error: <%= error.message %>'
          })(error);
          this.emit('end');
        }}))
      .pipe(concat('./index.js'))
      .pipe(gulp.dest('./assets/scripts/'))
      .pipe(rename(config.js.file))
      .pipe(uglify())
      .pipe(gulp.dest(config.js.dest));
});

// Concat all JS files into one file.
gulp.task('scripts_dev', function() {
  return gulp.src(config.js.src)
      .pipe(plumber({
        errorHandler: function (error) {
          notify.onError({
            title: 'Gulp scripts processing',
            subtitle: 'Failure!',
            message: 'Error: <%= error.message %>'
          })(error);
          this.emit('end');
        }}))
      .pipe(concat('./index.js'))
      .pipe(gulp.dest('./assets/scripts/'))
      .pipe(sourcemaps.init())
      .pipe(rename(config.js.file))
      .pipe(sourcemaps.write('./'))
      .pipe(gulp.dest(config.js.dest))
      .pipe(browserSync.reload({ stream: true, match: '**/*.js' }))
      .pipe(notify({message: 'Rebuild all custom scripts. Please refresh your browser', onLast: true}));
});

// Remove temporary JS storage.
gulp.task('removeTemporaryStorage', function() {
  return del('./assets/scripts/');
});

// Remove sourcemaps.
gulp.task('removeSourceMaps', function() {
  return del(['./css/style.css.map', './js/csgov_theme.js.map']);
});

// Move FontAwesome font files into final destination.
gulp.task('moveFontAwesome', function() {
  return gulp.src(config.fontawesome.src)
      .pipe(plumber({
        errorHandler: function (error) {
          notify.onError({
            title: 'Gulp scripts processing',
            subtitle: 'Failure!',
            message: 'Error: <%= error.message %>'
          })(error);
          this.emit('end');
        }}))
      .pipe(gulp.dest(config.fontawesome.dest));
});

// Watch task.
gulp.task('watch', function() {
  gulp.watch(config.css.src, { usePolling: true }, gulp.series('css_dev'));
  gulp.watch(config.js.src, { usePolling: true }, gulp.series('scripts_dev', 'removeTemporaryStorage'));
});

// JS Linting.
gulp.task('js-lint', function() {
  return gulp.src(config.js.src)
      .pipe(jshint())
      .pipe(jshint.reporter('default'));
});

// BrowserSync settings.
gulp.task('browserSync', function() {
  browserSync.init({
    proxy: configLocal.browserSyncProxy,
  });
});

// Compile for production.
gulp.task('serve', gulp.parallel('css', gulp.series('scripts', 'removeTemporaryStorage'), 'moveFontAwesome', 'images', 'removeSourceMaps'));

// Compile for development + BrowserSync + Watch
gulp.task('serve_dev', gulp.series(gulp.parallel('css_dev', gulp.series('scripts_dev', 'removeTemporaryStorage'), 'moveFontAwesome'), gulp.parallel('watch', 'browserSync')));

// Default Task
gulp.task('default', gulp.series('serve'));

// Development Task
gulp.task('dev', gulp.series('serve_dev'));
