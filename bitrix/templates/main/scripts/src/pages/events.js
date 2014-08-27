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

	$eventsList.find('.event_item ul.preview_photos li img').each(function () {
		var $img = $(this);
		var src = $img.attr('src');

		function grayscale() {
			var loadImg = require('load_img');
			var grayscaleImg = require('../basics/grayscale_img');
			grayscaleImg(src, function (err, dataURL) {
				if (err) {
					if (err instanceof loadImg.exceptions.Timeout) {
						setTimeout(grayscale, 0); // try again
						return;
					}
					require('../just_log_error')(err);
					return;
				}

				$img.attr('src', dataURL).addClass('finished');
			}); // grayscaleImg()
		} // grayscale()

		setTimeout(grayscale, 0);
	});

	var $photos = $('html.event_detail_page body main section.event_detail');
	$photos = $photos.find('ul.preview_photos>li a');

	if ($photos.size() > 0) {
		require('jquery.colorbox');
		$photos.colorbox(getVal('galleryColorboxParams'));
	}

}); // ready()
