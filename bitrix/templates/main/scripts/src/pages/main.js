/**
 * Main page behavior
 *
 * @author Viacheslav Lotsmanov
 */

define(['jquery', 'styles_ready'],
function ($, stylesReady) {
stylesReady(function () {

if (!$('html').hasClass('main_page')) return;
require(['get_val', 'relative_number'], function (getVal, relativeNumber) {

	// values
	var minW = getVal('minWidth');
	var rMaxW = getVal('relativeMaxWidth');
	var card1Ratio = [980, 550]; // actually in psd is 980:644, but by 2560 is calculate to 551.25, that is middle point
	var picWidthMin = 539;
	var picWidthMax = 837;
	var picTopMin = 56;
	var picTopMax = 288;
	var picOffsetMin = 134;
	var picOffsetMax = 0;
	var logoWidthMin = 174;
	var logoWidthMax = 242;
	var logoLeftMin = 16;
	var logoLeftMax = 15;
	var logoTxtFontSizeMin = 11;
	var logoTxtFontSizeMax = 15;
	var logoTxtLetterSpacingMin = 2;
	var logoTxtLetterSpacingMax = 3.3;
	var logoTxtMarginTopMin = 16;
	var logoTxtMarginTopMax = logoTxtMarginTopMin + 3;
	var nextCardBottomMin = 58;
	var nextCardBottomMax = 140;
	var nextCardSizeMin = 64;
	var nextCardSizeMax = 96;

	// dom elements
	var $header = $('header');
	var $footer = $('footer');
	var $main = $('main');
	var $w = $(window);
	var $scroll = $('html,body');
	var $topCard = $main.find('.top_card');

	$topCard.each(function () { // {{{1

		var $card = $(this);
		var $logo = $card.find('div.logo');
		var $logoImg = $logo.find('img.logo');
		var $logoTxt = $logo.find('.istablish');
		var $slider = $card.find('.slider');
		var $nextCard = $card.find('.next_card');

		var bindSuffix = '.top_card_resize';

		var resizeHandler = $.proxy(setTimeout, null, function () { // {{{2
			var w = $footer.width();
			var wh = $w.height();

			// reset first
			$card.css('height', '');
			$slider.css({
				width: '',
				top: '',
				'margin-left': '',
			});
			$logo.css('padding-left', '');
			$logoImg.css('width', '');
			$logoTxt.css({
				width: '',
				'font-size': '',
				'line-height': '',
				'letter-spacing': '',
				'margin-top': '',
			});
			$nextCard.css({
				'width': '',
				'height': '',
				'margin-left': '',
				'border-radius': '',
				'bottom': '',
			});

			// set card height
			$card.css('height', wh + 'px');
			var ch = $card.height(); // 'cause card has min-height

			// relative by card height
			var rh = w * card1Ratio[1] / card1Ratio[0];
			var rw = (ch < rh) ? ( ch * card1Ratio[0] / card1Ratio[1] ) : w;

			// offset for header (because header on main page is before card1)
			$header.css('top', ch + 'px');

			function rn(min, max) {
				return relativeNumber({
					relVal: rw,
					relMin: minW,
					relMax: rMaxW,
					min: min,
					max: max,
				});
			} // rn()

			var sliderW = rn(picWidthMin, picWidthMax);
			$slider.css({
				width: sliderW + 'px',
				top: (
					rn(picTopMin, picTopMax) +
					((ch > (rw * card1Ratio[1] / card1Ratio[0])) ? (
						((ch - (rw * card1Ratio[1] / card1Ratio[0])) / 2)
					) : 0)
				) + 'px',
				'margin-left': (
					-(sliderW / 2) + rn(picOffsetMin, picOffsetMax)
				) + 'px',
			});
			$logo.css('padding-left', rn(logoLeftMin, logoLeftMax) + 'px');
			$logoImg.css('width', rn(logoWidthMin, logoWidthMax) + 'px');
			$logoTxt.css({
				width: rn(logoWidthMin, logoWidthMax) + 'px',
				'font-size': rn(logoTxtFontSizeMin, logoTxtFontSizeMax) + 'px',
				'line-height': (rn(logoTxtFontSizeMin, logoTxtFontSizeMax) + 2) + 'px',
				'letter-spacing': rn(logoTxtLetterSpacingMin, logoTxtLetterSpacingMax) + 'px',
				'margin-top': rn(logoTxtMarginTopMin, logoTxtMarginTopMax) + 'px',
			});
			var ncW = rn(nextCardSizeMin, nextCardSizeMax);
			$nextCard.css({
				'width': ncW + 'px',
				'height': ncW + 'px',
				'margin-left': (-(ncW / 2)) + 'px',
				'border-radius': (ncW / 2) + 'px',
				'bottom': rn(nextCardBottomMin, nextCardSizeMax) + 'px',
			});
		}, 1); // resizeHandler() }}}2

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

	// click on next card button handler
	$main.find('.next_card').on('click', function () {
		$scroll.animate({ scrollTop: $topCard.height() + 'px' }, {
			duration: getVal('animationSpeed')*4,
			easing: getVal('animationCurve'),
		});
		return false;
	});

}); // require() for main page passed

}); // stylesReady()
}); // define()
