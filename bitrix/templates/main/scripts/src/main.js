/**
 * Main module
 *
 * @author Viacheslav Lotsmanov
 */

var $ = require('jquery');

var basics = require('./basics');

function factory(window, document, undefined) {
	var Modernizr = require('modernizr'); // init <html> Modernizr classes
	var getVal = basics.getVal;

	if (getVal('debug') && Modernizr.localstorage) {
		var ls = window.localStorage;
		if (
			!ls.revision ||
			ls.revision.toString() !== getVal('revision').toString()
		) {
			// clear less cache
			ls.clear();
			ls.revision = getVal('revision');
		}
	}
}

basics.init(function () {
	$(function () {
		factory(window, window.document);
	});
});

/*
	require(['jquery', 'modernizr', 'jquery.easing'], function ($) {
		$(function domReady() {
			var $html = $('html');

			if ($html.hasClass('main_page')) require(['pages/main']);
			if ($('main.shops').size() > 0) require(['pages/shops']);
			if ($html.hasClass('collection_page')) require(['pages/collection']);
			if ($('.events_list').size() > 0 || $html.hasClass('event_detail_page')) require(['pages/events']);
			if ($html.hasClass('error_404_page')) require(['pages/error_404']);

			if ($('header').size() > 0) require(['header']);
		}); // domReady()
	});
*/
