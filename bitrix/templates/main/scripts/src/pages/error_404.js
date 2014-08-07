/**
 * "error 404" page behavior
 *
 * @author Viacheslav Lotsmanov
 */

define(['jquery', 'styles_ready'],
function ($, stylesReady) {
stylesReady(function () {

var $html = $('html');
if (!$html.hasClass('error_404_page')) return;
require(['get_val'],
function (getVal) {

	var $header = $('header');
	var $footer = $('footer');
	var $w = $(window);

	var hh = $header.height();
	var fh = $footer.height();

	var bindSuffix = '.error_404_page_bind';

	$('.error_404').each(function () {
		var $block = $(this);
		var $wrap = $block.find('>.wrap');
		var mh = $wrap.innerHeight();

		var handler = $.proxy(setTimeout, null, function () {
			$wrap.css('height', '');
			var wrh = $w.height() - hh - fh;
			if (wrh > mh) $wrap.css('height', wrh + 'px');
		}, 1);

		$w.on('resize' + bindSuffix, handler);
		handler();
	});

}); // require() for page passed

}); // stylesReady()
}); // define()
