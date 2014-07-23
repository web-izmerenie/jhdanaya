/**
 * Header behavior
 *
 * @author Viacheslav Lotsmanov
 */

define(['get_val', 'jquery'], function (getVal, $) {
$(function domReady() {

	var $header = $('header');

	if ($header.size() <= 0) return;

	var $html = $('html');
	var $w = $(window);
	var $d = $(document);
	var $img = $header.find('img.logo');

	var bindSuffix = '.header';
	var fClass = getVal('fixedHeaderHTMLClass');
	var headerH = $header.height();
	var	imgSrc = $img.prop('src');
	var smallImgSrc = imgSrc.replace(/[^\/]+\.png/g, getVal('headerSmallLogoURL'));

	var mainPage = $html.hasClass('main_page');
	var $topCard;

	if (mainPage) $topCard = $('main .top_card');

	var handler = $.proxy(setTimeout, null, function () {
		var hVal = headerH;
		if (mainPage) hVal += $topCard.height();

		if ($d.scrollTop() >= hVal) {
			$html.addClass( fClass );
			$img.prop('src', smallImgSrc);
		} else {
			$html.removeClass( fClass );
			$img.prop('src', imgSrc);
		}
	}, 1);

	$w
		.on('scroll' + bindSuffix, handler)
		.on('resize' + bindSuffix, handler);

}); // domReady()
}); // define()

