var gulp 		= require('gulp'),
	minifyCSS 	= require('gulp-minify-css'),
	concatCss 	= require('gulp-concat-css'),
	concatJS	= require('gulp-concat'),
	notify 		= require('gulp-notify'),
	uglify 		= require('gulp-uglify');


gulp.task('css',function(){

});
gulp.task('js',function(){
	gulp.src(['htdocs/js/libs/*.js','htdocs/js/externos/ajaxupload.js'])
		.pipe(concatJS('jd.plugs.js'))
		.pipe(uglify())
		.pipe(gulp.dest('htdocs/js/dist'))
		.pipe(notify("compilados archivos js"));

});
