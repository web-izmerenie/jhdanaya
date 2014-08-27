/**
 * "shops" page behavior
 *
 * @author Viacheslav Lotsmanov
 */

var $ = require('jquery');
var ready = require('../ready');
var basics = require('../basics');

ready(function (window, document, undefined) {

	var $main = $('main.shops');

	if ($main.size() <= 0) return;

	var getVal = basics.getVal;
	var relativeNumber = require('../basics/relative_number');

	// values
	var minW = getVal('minWidth');
	var maxW = getVal('maxWidth');
	var rIntervalMin = 10;
	var rIntervalMax = 16;
	var imapRatio = [313, 213];

	// dom elements
	var $footer = $('footer');

	var dynamicLoadApi = basics.dynamicLoadApi;

	var yamapApiUrl =
		'http://api-maps.yandex.ru/2.0/?load=package.standard&lang=' +
		((getVal('lang') === 'ru') ? 'ru-RU' : 'en-US');

	function initYaMap(id, x, y, zoom, cb) { // {{{1
		dynamicLoadApi(
			yamapApiUrl,
			'ymaps',
			function (err, ymaps) {
				if (err) return setTimeout($.proxy(cb, null, err), 0);

				ymaps.ready(function () {
					var map, mark;

					try {
						map = new ymaps.Map(id, {
							center: [ y, x ],
							zoom: zoom,
						});
					} catch (err) {
						return setTimeout($.proxy(cb, null, err), 0);
					}

					try {
						mark = new ymaps.Placemark([ y, x ]);
					} catch (err) {
						return setTimeout($.proxy(cb, null, err), 0);
					}

					map.geoObjects.add(mark);

					if (cb) setTimeout($.proxy(cb, null, null, map), 0);
				});
			}
		); // dynamicLoadApi()
	} // initYaMap() }}}1

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
		}, 0); // resizeHandler() }}}2

		$imaps.each(function (i) { // {{{2
			var $map = $(this);
			var id = 'interactive_yandex_map_n_' + i;
			$map.attr('id', id);
			initYaMap(
				id,
				parseFloat($map.attr('data-coord-x')),
				parseFloat($map.attr('data-coord-y')),
				parseInt($map.attr('data-zoom'), 10),
				function (err, map) {
					if (err) throw err;

					$map.data('yamap', map);
				});
		}); // $imaps }}}2

		$(window).on('resize' + bindSuffix, resizeHandler);
		resizeHandler();
	}); // $main }}}1

}); // ready()
