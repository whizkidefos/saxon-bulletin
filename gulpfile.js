const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const babel = require('gulp-babel');
const terser = require('gulp-terser');
const concat = require('gulp-concat');
const rename = require('gulp-rename');
const sourcemaps = require('gulp-sourcemaps');
const browserSync = require('browser-sync').create();
const plumber = require('gulp-plumber');
const imagemin = require('gulp-imagemin');

// Project paths
const paths = {
  styles: {
    src: 'assets/scss/**/*.scss',
    dest: 'dist/css'
  },
  scripts: {
    src: 'assets/js/**/*.js',
    dest: 'dist/js'
  },
  images: {
    src: 'assets/images/**/*',
    dest: 'dist/images'
  },
  php: '**/*.php'
};

// WordPress theme directory name
const themeName = 'saxon-bulletin';

// BrowserSync config
const browserSyncConfig = {
  proxy: 'saxon-bulletin.local',  // Change this to match your local development URL
  notify: false,
  files: [
    `wp-content/themes/${themeName}/**/*.php`,
    `wp-content/themes/${themeName}/dist/css/**/*.css`,
    `wp-content/themes/${themeName}/dist/js/**/*.js`
  ]
};

// Sass task
function styles() {
  return gulp.src(paths.styles.src)
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(sass({
      includePaths: ['node_modules']
    }).on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(cleanCSS())
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(browserSync.stream());
}

// JavaScript task
function scripts() {
  return gulp.src(paths.scripts.src)
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(babel({
      presets: ['@babel/preset-env']
    }))
    .pipe(terser())
    .pipe(concat('theme.min.js'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.scripts.dest))
    .pipe(browserSync.stream());
}

// Image optimization task
function images() {
  return gulp.src(paths.images.src)
    .pipe(imagemin())
    .pipe(gulp.dest(paths.images.dest));
}

// Watch files
function watch() {
  browserSync.init(browserSyncConfig);
  
  gulp.watch(paths.styles.src, styles);
  gulp.watch(paths.scripts.src, scripts);
  gulp.watch(paths.images.src, images);
  gulp.watch(paths.php).on('change', browserSync.reload);
}

// Build task
const build = gulp.parallel(styles, scripts, images);

// Development task
const dev = gulp.series(build, watch);

// Export tasks
exports.styles = styles;
exports.scripts = scripts;
exports.images = images;
exports.watch = watch;
exports.build = build;
exports.default = dev;