/**
 * "events" pages behavior
 *
 * @author Viacheslav Lotsmanov
 */

var $ = require('jquery');
var ready = require('../ready');
var basics = require('../basics');

ready(function (window, document, undefined) {

	var $html = $('html');
	var $eventsList = $('.events_list');

	if ($eventsList.size() <= 0 && !$html.hasClass('event_detail_page')) return;

	var getVal = basics.getVal;

	var $photos = $('html.event_detail_page body main section.event_detail');
	$photos = $photos.find('ul.preview_photos>li a');

	if ($photos.size() > 0) {
		require('jquery.colorbox');
		$photos.colorbox(getVal('galleryColorboxParams'));
	}

}); // ready()
