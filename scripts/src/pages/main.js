/**
 * Main page behavior
 *
 * @author Viacheslav Lotsmanov
 */

define(['jquery', 'get_val'], function ($, getVal) {
$(function domReady() {

	if (!$('html').hasClass('main_page')) return;

	var $header = $('header');
	var $footer = $('footer');
	var $main = $('main');
	var $w = $(window);
	var $scroll = $('html,body');
	var $topCard = $main.find('.top_card');

	var minW = getVal('minWidth');
	var maxW = getVal('maxWidth');

	$topCard.each(function () {
		var $card = $(this);
		var bindSuffix = '.top_card_resize';

		var resizeHandler = $.proxy(setTimeout, null, function () {
			var w = $footer.width();
			var h = $w.height();

			if (w < minW) w = minW;
			else if (w > maxW) w = maxW;

			$card.css('height', h + 'px');
			$header.css('top', $card.height() + 'px');
		}, 1);

		$w.on('resize' + bindSuffix, resizeHandler);
		resizeHandler();
	});

	$main.find('.next_card').on('click', function () {
		require(['jquery.easing'], function ($) {
			$scroll.animate({ scrollTop: $topCard.height() + 'px' }, {
				duration: getVal('animationSpeed')*4,
				easing: 'easeInOutQuad'
			});
		});
		return false;
	});

}); // domReady()
}); // define()
