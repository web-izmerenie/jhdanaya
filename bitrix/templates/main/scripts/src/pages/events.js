/**
 * "events" pages behavior
 *
 * @author Viacheslav Lotsmanov
 */

define(['jquery', 'styles_ready'],
function ($, stylesReady) {
stylesReady(function () {

var $eventsList = $('.events_list');
if ($eventsList.size() <= 0) return;
require(['get_val', 'get_local_text', 'relative_number'],
function (getVal, getLocalText, relativeNumber) {

	$eventsList.find('.event_item ul.preview_photos li img').each(function () {
		var $img = $(this);
		var src = $img.attr('src');

		function grayscale() {
			require(['load_img', 'grayscale_img'],
			function (loadImg, grayscaleImg) {
				grayscaleImg(src, function (err, dataURL) {
					if (err) {
						if (err instanceof loadImg.exceptions.Timeout) {
							setTimeout(grayscale, 1); // try again
							return;
						}
						if (window.console && window.console.error) {
							window.console.error( err );
						}
						return;
					}

					$img.attr('src', dataURL).addClass('finished');
				});
			});
		}

		setTimeout(grayscale, 1);
	});

}); // require() for page passed

}); // stylesReady()
}); // define()
