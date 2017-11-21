var elixir = require('laravel-elixir');
var gulp = require('gulp');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
var js = [
    'jQuery-2.1.4.min.js',
    'bootstrap.min.js',
    'gushi/common.js'
];
elixir(function(mix) {
    mix.sass('app.scss')
        .scripts(js, './public/js/app.js')
        .version([
            'css/app.css',
            'js/app.js'
        ]);
});
// var elixir = require('laravel-elixir');

// elixir(function(mix) {
//     mix.less('admin-lte/AdminLTE.less', 'public/la-assets/css');
//     mix.less('bootstrap/bootstrap.less', 'public/la-assets/css');
// });

/*
var minify = require('gulp-minify');
gulp.task('compress', function() {
  gulp.src('lib/*.js')
    .pipe(minify({
        ext:{
            src:'-debug.js',
            min:'.js'
        },
        exclude: ['tasks'],
        ignoreFiles: ['.combo.js', '-min.js']
    }))
    .pipe(gulp.dest('dist'))
});
*/
//监视文件变化，路径下文件发生变化执行
gulp.task('watch', function() {
    // 看守所有.scss文档，发生变化时执行压缩程序
    gulp.watch(['resources/assets/sass/**/*.scss'] ,['default']);
});