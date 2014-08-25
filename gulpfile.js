/**
 * @version r1
 * @author Viacheslav Lotsmanov
 */

var pkg = require('./package.json');

var path = require('path');

var gulp = require('gulp');
var argv = require('yargs').argv;

var clean = require('gulp-clean');
var spritesmith = require('gulp.spritesmith');
var taskListing = require('gulp-task-listing');
var less = require('gulp-less');
var gulpif = require('gulp-if');
var rename = require('gulp-rename');

gulp.task('help', taskListing);

var production = argv.production ? true : false;

// clean {{{1

function cleanTask(list) { // {{{2
	if (!Array.isArray(list)) return gulp;
	var retval = null;
	list.forEach(function (item) {
		retval = gulp.src(item).pipe(clean({ force: true }));
	});
	return retval;
} // cleanTask() }}}2

gulp.task('clean', ['clean-sprites'], function () {
	return cleanTask(pkg.gulp.clean);
});

gulp.task('distclean', ['clean'], function () {
	return cleanTask(pkg.gulp.distclean);
});

// clean }}}1

// sprites {{{1

var spritesCleanTasks = [];
var spritesBuildTasks = [];

function spriteCleanTask(name, spriteParams, params) { // {{{2
	gulp
		.src(path.join(params.imgDir, 'build/'))
		.pipe(clean({ force: true }));
	return gulp
		.src(path.join(params.cssDir, spriteParams.cssName))
		.pipe(clean({ force: true }));
} // spriteCleanTask() }}}2

function spriteBuildTask(name, spriteParams, params) { // {{{2
	var spriteData = gulp
		.src(path.join(params.imgDir, 'src/*.png'))
		.pipe(spritesmith( spriteParams ));

	spriteData.img.pipe(gulp.dest(path.join(params.imgDir, 'build/')));
	return spriteData.css.pipe(gulp.dest( params.cssDir ));
} // spriteBuildTask() }}}2

// create tasks by package.json {{{2
Object.keys(pkg.gulp.sprites).forEach(function (name) {
	var item = pkg.gulp.sprites[name];

	var imgName = item.img_name || 'sprite.png';
	var spriteParams = {
		imgName: imgName,
		cssName: item.css_name || name + '.css',
		imgPath: path.join(item.img_path_prefix, 'build/', imgName),
		padding: item.padding || 1,
		imgOpts: { format: 'png' },
		cssVarMap: function (s) {
			s.name = 'sprite_' + name + '_' + s.name;
		},
		algorithm: item.algorithm || 'top-down',
	};

	var params = {
		imgDir: item.img_dir,
		cssDir: item.css_dir,
	};

	gulp.task(
		'clean-sprite-' + name,
		spriteCleanTask.bind(null, name, spriteParams, params)
	);
	gulp.task(
		'sprite-' + name,
		['clean-sprite-' + name],
		spriteBuildTask.bind(null, name, spriteParams, params)
	);

	spritesCleanTasks.push('clean-sprite-' + name);
	spritesBuildTasks.push('sprite-' + name);
}); // create tasks by package.json }}}2

gulp.task('clean-sprites', spritesCleanTasks);
gulp.task('sprites', spritesBuildTasks);

// sprites }}}1

// less {{{1

var lessCleanTasks = [];
var lessBuildTasks = [];

function lessCleanTask(name, params) { // {{{2
	return gulp
		.src(path.join(params.path, 'build/'))
		.pipe(clean({ force: true }));
} // lessCleanTask() }}}2

function lessBuildTask(name, params) { // {{{2
	return gulp
		.src(path.join(params.path, 'src/', params.mainSrc))
		.pipe(less({
			compress: production,
		}))
		.pipe(rename(function (buildPath) {
			buildPath.extname = path.extname(params.buildFile);
			buildPath.basename = path.basename(params.buildFile, buildPath.extname);
		}))
		.pipe(gulp.dest(path.join(params.path, 'build/')));
} // lessBuildTask() }}}2

// create tasks by package.json {{{2
Object.keys(pkg.gulp.less).forEach(function (name) {
	var item = pkg.gulp.less[name];

	var params = {
		path: item.path,
		mainSrc: item.main_src,
		buildFile: item.build_file,
	};

	var requiredSprites = [];
	if (item.required_sprites)
		item.required_sprites.forEach(function (spriteName) {
			requiredSprites.push('sprite-' + spriteName);
		});

	gulp.task(
		'clean-less-' + name,
		requiredSprites,
		lessCleanTask.bind(null, name, params)
	);
	gulp.task(
		'less-' + name,
		['clean-less-' + name],
		lessBuildTask.bind(null, name, params)
	);

	lessCleanTasks.push('clean-less-' + name);
	lessBuildTasks.push('less-' + name);
}); // create tasks by package.json }}}2

gulp.task('clean-less', lessCleanTasks);
gulp.task('less', lessBuildTasks);

// less }}}1

gulp.task('default', ['sprites', 'less']);
