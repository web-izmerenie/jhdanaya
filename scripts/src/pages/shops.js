/**
 * "shops" page behavior
 *
 * @author Viacheslav Lotsmanov
 */

define(['jquery', 'styles_ready'],
function ($, stylesReady) {
stylesReady(function () {

var $main = $('main.shops');
if ($main.size() <= 0) return;
require(['get_val', 'relative_number'], function (getVal, relativeNumber) {

	// values
	var minW = getVal('minWidth');
	var maxW = getVal('maxWidth');
	var rIntervalMin = 10;
	var rIntervalMax = 16;

	// dom elements
	var $footer = $('footer');

	$main.each(function () { // {{{1
		var $main = $(this);
		var $rInterval = $main.find('>ul.shops_list>li .photos .right_col>*+*');

		var bindSuffix = '.shops_page_right_interval';

		var resizeHandler = $.proxy(setTimeout, null, function () { // {{{2
			var w = $footer.width();

			$rInterval.css('margin-top', '');
			$rInterval.css('margin-top', relativeNumber({
				relVal: w,
				relMin: minW,
				relMax: maxW,
				min: rIntervalMin,
				max: rIntervalMax,
			}) + 'px');
		}, 1); // resizeHandler() }}}2

		$(window).on('resize' + bindSuffix, resizeHandler);
		resizeHandler();
	}); // $main }}}1

}); // require() for page passed

}); // stylesReady()
}); // define()
