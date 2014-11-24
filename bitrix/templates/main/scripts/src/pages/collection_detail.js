/**
 * "collection" detail pages behavior
 *
 * @author Viacheslav Lotsmanov
 * @license GNU/AGPLv3
 * @see {@link https://github.com/web-izmerenie/jhdanaya/blob/master/LICENSE-AGPLv3|License}
 */

var $ = require('jquery');
var ready = require('../ready');
var basics = require('../basics');

ready(function (window, document, undefined) {

	var $html = $('html');

	if (!$html.hasClass('collection_page')) return;

	var getVal = basics.getVal;

	var $main = $('main');
	var $c = $main.find('.collection_detail');
	var $content = $c.find('.detail_content');
	var $picture = $content.find('.picture');
	var $img = $picture.find('img');
	var $showBig = $picture.find('.show_big');

	var imgSrc = $img.attr('src');

	if (imgSrc) {
		$('<img/>').load(function () {
			$img.css('max-width', this.width + 'px');
		}).attr('src', imgSrc);
	}

	$showBig.click(function () { // {{{1

		var process = false;

		$html
			.addClass('collection_page_over_popup')
			.addClass('collection_page_big_photo');

		var $block = $('<div/>').addClass('collection_page_big_photo_block');
		var $closer = $('<a/>').addClass('closer');
		var $wrap = $('<div/>').addClass('wrap');
		var $img = $('<img/>').attr('src', imgSrc);

		$wrap.append($img);
		$block.append($closer).append($wrap);

		$('body').append($block);

		$block.stop().animate(
			{opacity: 1},
			getVal('animationSpeed'),
			getVal('animationCurve'), function () {
				// show big photo {{{2

				$img.on('click', function () { return false; });

				$closer.on('click', function () {
					if (process) return false; else process = true;

					$block.animate(
						{ opacity: 0 },
						getVal('animationSpeed'),
						getVal('animationCurve'), function () {
							$html
								.removeClass('collection_page_big_photo')
								.removeClass('collection_page_over_popup');
							$block.remove();
						});

					return false;
				});

				$block.on('click', function () {
					$closer.trigger('click');
					return false;
				});

				// show big photo }}}2
			});

		return false;
	}); // $showBig.click() }}}1

});
