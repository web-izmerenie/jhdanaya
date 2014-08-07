/**
 * Main module
 *
 * @author Viacheslav Lotsmanov
 */

define(['basics/get_val'], function (getVal) {

	var aliasMap = {
		'modernizr': 'libs/modernizr.custom.01922',

		// basics
		'get_local_text': 'basics/get_local_text',
		'get_val': 'basics/get_val',
		'relative_number': 'basics/relative_number',
		'dynamic_api': 'basics/dynamic_api',
		'load_img': 'basics/load_img',
		'blur_img': 'basics/blur_img',
		'json_answer': 'basics/json_answer',
		'grayscale_img': 'basics/grayscale_img',
	};

	var paths = {};

	if (getVal('clientSide')) {
		paths.jquery = 'libs/jquery-2.1.1';
		paths['jquery.easing'] = 'libs/jquery.easing-1.3';
		paths.less = 'fat/less-1.7.3';
	}

	require.config({
		map: { '*': aliasMap },
		paths: paths,
		enforceDefine: true,
	}); // require.config()

	if (getVal('clientSide')) {
		// less on client side
		require(['modernizr'], function (Modernizr) {
			if (Modernizr.localstorage) {
				var ls = window.localStorage;
				if (!ls.revision || ls.revision.toString() !== getVal('revision').toString()) {
					// clear less cache
					ls.clear();
					ls.revision = getVal('revision');
				}
				require(['less']);
			} else require(['less']);
		});
	}

	require(['jquery', 'modernizr', 'jquery.easing'], function ($) {
		$(function domReady() {
			var $html = $('html');

			if ($html.hasClass('main_page')) require(['pages/main']);
			if ($('main.shops').size() > 0) require(['pages/shops']);
			if ($html.hasClass('collection_page')) require(['pages/collection']);
			if ($('.events_list').size() > 0) require(['pages/events']);
			if ($html.hasClass('error_404_page')) require(['pages/error_404']);

			if ($('header').size() > 0) require(['header']);
		}); // domReady()
	});

}); // define()
