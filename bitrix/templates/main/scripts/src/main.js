/**
 * Main module
 *
 * @author Viacheslav Lotsmanov
 * @license GNU/AGPLv3
 * @see {@link https://github.com/web-izmerenie/jhdanaya/blob/master/LICENSE-AGPLv3|License}
 */

var $ = require('jquery');
var basics = require('./basics');
var ready = require('./ready');

ready(function factory(window, document, undefined) {
	var Modernizr = require('modernizr'); // init <html> Modernizr classes
	require('jquery.easing'); // init jquery easings

	var getVal = basics.getVal;

	if (getVal('debug') && Modernizr.localstorage) { // clear less cache {{{1
		var ls = window.localStorage;
		if (
			!ls.revision ||
			ls.revision.toString() !== getVal('revision').toString()
		) {
			// clear less cache
			ls.clear();
			ls.revision = getVal('revision');
		}
	} // clear less cache }}}1

	var $html = $('html');

	if ($html.hasClass('main_page')) require('./pages/main');
	if ($('main.shops').size() > 0) require('./pages/shops');
	if ($html.hasClass('collection_page')) {
		if ($('main .collection_detail').size() <= 0) {
			require('./pages/collection');
		} else {
			require('./pages/collection_detail');
		}
	}
	if ($('.events_list').size() > 0 || $html.hasClass('event_detail_page')) {
		require('./pages/events');
	}
	if ($html.hasClass('error_404_page')) require('./pages/error_404');

	if ($('header').size() > 0) require('./header');
}); // ready()
