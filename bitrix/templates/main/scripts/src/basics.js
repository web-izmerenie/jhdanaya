/**
 * Basics modules instances
 *
 * @author Viacheslav Lotsmanov
 * @license GNU/AGPLv3
 * @see {@link https://github.com/web-izmerenie/jhdanaya/blob/master/LICENSE-AGPLv3|License}
 */

var $ = require('jquery');

module.exports = {
	getVal: null,
	getLocalText: null,
	dynamicLoadApi: null,
	loadImg: null,
};

// "dynamicLoadApi" {{{1

function initDynamicLoadApi() {
	var DynamicLoadApi = require('./basics/dynamic_api');
	module.exports.dynamicLoadApi = new DynamicLoadApi(
		module.exports.getVal('waiterInterval'));
	initTrigger();
}

// "dynamicLoadApi" }}}1

// "loadImg" {{{1

function initLoadImg() {
	var LoadImg = require('./basics/load_img');
	module.exports.loadImg = new LoadImg(
		module.exports.getVal('loadImgTimeout'));
	initTrigger();
}

// "loadImg" }}}1

// "getVal" {{{1

$(function () {
	var GetVal = require('./basics/get_val');
	var $html = $('html');
	module.exports.getVal = new GetVal(
		require('./values'),
		{
			'lang': $html.attr('lang'),
			'revision': $html.attr('data-revision'),
			'tplPath': $html.attr('data-template-path'),
			'debug': ($html.attr('data-debug').toLowerCase() === 'true') ? true : false,
		}
	);
	initTrigger();
	initDynamicLoadApi();
	initLoadImg();
});

// "getVal" }}}1

// "getLocalText" {{{1

$(function () {
	var GetLocalText = require('./basics/get_local_text');
	module.exports.getLocalText = new GetLocalText(
		require('./localization'), $('html').attr('lang'));
	initTrigger();
});

// "getLocalText" }}}1

var initCallbacks = [];

function initTrigger() {
	var no = false;
	for (var key in module.exports)
		if (!module.exports[key]) no = true;
	if (no) return;
	$.each(initCallbacks, function (i, cb) {
		setTimeout(cb, 0);
	});
	initCallbacks = [];
}

module.exports.init = function (cb) {
	initCallbacks.push(cb);
	initTrigger();
};
