var pkg = require('./package.json');

var path = require('path');
var gulp = require('gulp');

var clean = require('gulp-clean');
var spritesmith = require('gulp.spritesmith');
var taskListing = require('gulp-task-listing');

gulp.task('help', taskListing);

// sprites {{{1

var spritesCleanTasks = [];
var spritesBuildTasks = [];

function spriteTaskClean(spriteName, spriteParams, params) { // {{{2
	gulp
		.src(path.join(params.imgDir, 'build/'))
		.pipe(clean({ force: true }));
	return gulp
		.src(path.join(params.cssDir, spriteParams.cssName))
		.pipe(clean({ force: true }));
} // spriteTaskClean }}}2

function spriteTaskBuild(spriteName, spriteParams, params) { // {{{2
	var spriteData = gulp
		.src(path.join(params.imgDir, 'src/*.png'))
		.pipe(spritesmith( spriteParams ));

	spriteData.img.pipe(gulp.dest(path.join(params.imgDir, 'build/')));
	return spriteData.css.pipe(gulp.dest( params.cssDir ));
} // spriteTaskBuild }}}2

for (var key in pkg.gulp.sprites) {
	var sprite = pkg.gulp.sprites[key];
	var cssDir = sprite.css_dir;
	var imgDir = sprite.img_dir;

	spritesCleanTasks.push('clean-sprite-' + key);
	spritesBuildTasks.push('sprite-' + key);

	var imgName = sprite.img_name || 'sprite.png';
	var spriteParams = {
		imgName: imgName,
		cssName: sprite.css_name || key + '.less',
		imgPath: path.join(sprite.img_path_prefix, 'build/', imgName),
		padding: sprite.padding || 1,
		imgOpts: { format: 'png' },
		cssVarMap: function (s) {
			s.name = 'sprite_' + key + '_' + s.name;
		},
		algorithm: sprite.algorithm || 'top-down',
	};

	var params = {
		imgDir: sprite.img_dir,
		cssDir: sprite.css_dir,
	};

	gulp.task(
		'clean-sprite-' + key,
		spriteTaskClean.bind(null, key, spriteParams, params)
	);
	gulp.task(
		'sprite-' + key,
		spriteTaskBuild.bind(null, key, spriteParams, params)
	);
}

gulp.task('clean-sprites', spritesCleanTasks);
gulp.task('sprites', spritesBuildTasks);

// sprites }}}1

// clean {{{1

gulp.task('clean', ['clean-sprites'], function () {
	var list = pkg.gulp.clean;
	for (var i=0; i<list.length; i++) {
		var item = list[i];
		if (i < list.length - 1)
			gulp.src(item).pipe(clean({ force: true }));
		else
			return gulp.src(item).pipe(clean({ force: true }));
	}
});

gulp.task('distclean', ['clean'], function () {
	gulp.src('./gulp').pipe(clean({ force: true }));
	return gulp.src('./node_modules').pipe(clean({ force: true }));
});

// clean }}}1

gulp.task('default', ['sprites']);
