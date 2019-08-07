"use strict";

/************************
 * SETUP
 ************************/

var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');

/************************
 * CONFIGURATION
 ************************/

var stylesSrc = [
  './scss/layout-spacing.scss',
  './scss/layout-base.scss',
];

/************************
 * TASKS
 ************************/

gulp.task('styles', function() {
  gulp.src(stylesSrc)
    .pipe(sass({
      outputStyle: 'expanded',
      sourceComments: false
    }))
    // Catch any SCSS errors and prevent them from crashing gulp
    .on('error', function (error) {
      console.error(error);
      this.emit('end');
    })
    .pipe(autoprefixer({
      browsers: ['last 2 versions', 'ie 11']
    }))
    .pipe(gulp.dest('./css'));
});

gulp.task('watch', function() {
  gulp.watch('./scss/**/*.scss', ['styles']);
});

gulp.task('default', ['styles']);
