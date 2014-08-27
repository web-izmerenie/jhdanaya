/**
 * Header behavior
 *
 * @author Viacheslav Lotsmanov
 */

var $ = require('jquery');
var ready = require('./ready');
var basics = require('./basics');

ready(function () {

	var getVal = basics.getVal;
	var $header = $('header');

	if ($header.size() <= 0) return;

	var $html = $('html');
	var $w = $(window);
	var $d = $(document);
	var $img = $header.find('img.logo');

	var bindSuffix = '.header';
	var fClass = getVal('fixedHeaderHTMLClass');
	var headerH = $header.height();
	var smallHeaderH = parseInt($header.css('min-height'), 10);
	var imgSrc = $img.prop('src');
	var smallImgSrc = imgSrc.replace(/[^\/]+\.png/g, getVal('headerSmallLogoURL'));

	var mainPage = $html.hasClass('main_page');
	var $topCard;
	var error404Page = $html.hasClass('error_404_page');

	if (mainPage) $topCard = $('main .top_card');

	var handler = $.proxy(setTimeout, null, function () {
		var hVal = headerH - smallHeaderH;
		if (mainPage) hVal += $topCard.height();

		if ($d.scrollTop() >= hVal && !error404Page) {
			if (!$html.hasClass( fClass )) {
				$html.addClass( fClass );
				$img.prop('src', smallImgSrc);
			}
		} else {
			if ($html.hasClass( fClass )) {
				$html.removeClass( fClass );
				$img.prop('src', imgSrc);
				$header.stop().css('height', '');
			}
		}

		if ($html.hasClass( fClass )) {
			$header.css('left', (-$d.scrollLeft()) + 'px');
		} else {
			$header.css('left', '');
		}
	}, 0);

	$w
		.on('scroll' + bindSuffix, handler)
		.on('resize' + bindSuffix, handler);
	handler();

}); // ready()
