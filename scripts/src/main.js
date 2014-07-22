/**
 * Main module
 *
 * @author Viacheslav Lotsmanov
 */

define(['basics/get_val'], function (getVal) {

	var aliasMap = {
		// basics
		'get_local_text': 'basics/get_local_text',
		'get_val': 'basics/get_val',
	};

	var paths = {};

	if (getVal('clientSide')) {
		paths.jquery = 'libs/jquery-2.1.1';
		paths.less = 'fat/less-1.7.3';
	}

	require.config({
		map: { '*': aliasMap },
		paths: paths,
		enforceDefine: true,
	}); // require.config()

	if (getVal('clientSide')) {
		if (window.localStorage) {
			// clear less cache
			window.localStorage.clear();
		}
		require(['less']);
	}

	require(['jquery'], function ($) {
		$(function domReady() {
		}); // domReady()
	});

}); // define()
