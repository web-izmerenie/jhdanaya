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
	var imapRatio = [313, 213];

	// dom elements
	var $footer = $('footer');

	$main.each(function () { // {{{1
		var $main = $(this);
		var $list = $main.find('>ul.shops_list');
		var $listItems = $list.find('>li');
		var $photos = $listItems.find('.photos');
		var $rightCol = $photos.find('.right_col');
		var $rInterval = $rightCol.find('>*+*');
		var $imaps = $rightCol.find('.imap');

		var bindSuffix = '.shops_page_resize';

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

			var imapW = $imaps.width();
			$imaps.css('height', '');
			$imaps.css('height', (imapW * imapRatio[1] / imapRatio[0]) + 'px');
			$imaps.each(function () {
				var map = $(this).data('yamap');
				if (map) map.container.fitToViewport();
			});
		}, 1); // resizeHandler() }}}2

		$imaps.each(function (i) { // {{{2
			var $map = $(this);
			var id = 'interactive_yandex_map_n_' + i;
			$map.attr('id', id);
			require(['dynamic_api'], function (dynamicLoadApi) {
				var mapLang = (getVal('lang') === 'ru') ? 'ru-RU' : 'en-US';
				dynamicLoadApi(
					'http://api-maps.yandex.ru/2.0/?load=package.standard&lang=' + mapLang,
					'ymaps',
					function (err, ymaps) {
						if (err) throw err;
						ymaps.ready(function () {
							var map = new ymaps.Map(id, {
								center: [
									parseFloat($map.attr('data-coord-y')),
									parseFloat($map.attr('data-coord-x'))
								],
								zoom: parseInt($map.attr('data-zoom'), 10)
							});

							var mark = new ymaps.Placemark([
								$map.attr('data-coord-y'),
								$map.attr('data-coord-x')
							]);

							map.geoObjects.add(mark);

							$map.data('yamap', map);
						});
					}
				); // dynamicLoadApi()
			}); // require(['dynamic_api']...
		}); // $imaps }}}2

		$(window).on('resize' + bindSuffix, resizeHandler);
		resizeHandler();
	}); // $main }}}1

}); // require() for page passed

}); // stylesReady()
}); // define()
