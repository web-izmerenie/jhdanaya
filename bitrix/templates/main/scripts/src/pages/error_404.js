/**
 * "error 404" page behavior
 *
 * @author Viacheslav Lotsmanov
 * @license GNU/AGPLv3
 * @see {@link https://github.com/web-izmerenie/jhdanaya/blob/master/LICENSE-AGPLv3|License}
 */

var $ = require('jquery');
var ready = require('../ready');

ready(function (window, document, undefined) {

	var $html = $('html');

	if (!$html.hasClass('error_404_page')) return;

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
		}, 0);

		$w.on('resize' + bindSuffix, handler);
		handler();
	});

}); // ready()
