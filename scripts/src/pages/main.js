/**
 * Main page behavior
 *
 * @author Viacheslav Lotsmanov
 */

define(['jquery', 'get_val', 'styles_ready'], function ($, getVal, stylesReady) {
stylesReady(function () {

	if (!$('html').hasClass('main_page')) return;

	var $header = $('header');
	var $footer = $('footer');
	var $main = $('main');
	var $w = $(window);
	var $scroll = $('html,body');
	var $topCard = $main.find('.top_card');

	var minW = getVal('minWidth');
	var maxW = getVal('maxWidth');

	$topCard.each(function () { // {{{1

		var $card = $(this);
		var $slider = $card.find('.slider');

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

		$slider.each(function () { // {{{2
			var $slider = $(this);
			var $pics = $slider.find('img');

			if ($pics.size() < 2) {
				$pics.eq(0).addClass('current').addClass('visible');
				return;
			}

			if ($pics.filter('.current').size() < 1) {
				$pics.eq(0).addClass('current').addClass('visible');
			}

			var loop = $.proxy(setTimeout, null, function () {
				var $cur = $pics.filter('.current');
				var $next = $cur.next();
				if ($next.size() < 1) $next = $pics.eq(0);

				$next.addClass('current visible');
				$cur.removeClass('visible');
				setTimeout(function () { $cur.removeClass('current'); }, 1000);

				setTimeout(loop, getVal('animationSpeed') * 4);
			}, getVal('mainPageSliderInterval') * 1000);

			loop();
		}); // $slider }}}2

	}); // $topCard }}}1

	$main.find('.next_card').on('click', function () {
		$scroll.animate({ scrollTop: $topCard.height() + 'px' }, {
			duration: getVal('animationSpeed')*4,
			easing: getVal('animationCurve'),
		});
		return false;
	});

}); // stylesReady()
}); // define()
