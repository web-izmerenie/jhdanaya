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
	var dynamicLoadApi = basics.dynamicLoadApi;

	// values
	var minW = getVal('minWidth');
	var maxW = getVal('maxWidth');
	var rIntervalMin = 10;
	var rIntervalMax = 16;
	var imapRatio = [313, 213];

	// dom elements
	var $footer = $('footer');
	var $html = $('html');
	var $body = $html.find('>body');

	var yamapApiUrl =
		'http://api-maps.yandex.ru/2.0/?load=package.standard&lang=' +
		((getVal('lang') === 'ru') ? 'ru-RU' : 'en-US');

	function initYaMap(id, x, y, zoom, cb, params) { // {{{1
		params = $.extend({
			zoomSlider: false,
			mapTypeSelect: false,
		}, (params || {}));

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

					if (params.zoomSlider)
						map.controls.add('zoomControl', { left: 15, top: 15 });

					if (params.mapTypeSelect)
						map.controls.add('typeSelector', { left: 15, bottom: 15 });

					map.geoObjects.add(mark);

					if (cb) setTimeout($.proxy(cb, null, null, map), 0);
				});
			}
		); // dynamicLoadApi()
	} // initYaMap() }}}1

	function mapResizeHandler() {
		var map = $(this).data('yamap');
		if (map) map.container.fitToViewport();
	} // mapResizeHandler()

	function openBigMap(x, y, zoom) { // {{{1
		if ($html.hasClass('shops_page_big_map'))
			return false;
		else
			$html.addClass('shops_page_big_map');

		var id = 'interactive_yandex_big_map';
		var bindSuffix = '.interactive_yandex_big_map';
		var closing = false;
		var animationSpeed = getVal('animationSpeed') * 4;

		var $block = $('<div/>', { class: 'big_map' });
		var $wrap = $('<div/>', { class: 'wrap' });
		var $overlay = $('<div/>', { class: 'overlay' });
		var $content = $('<div/>', { class: 'content' });
		var $closer = $('<a/>', { class: 'closer' });
		var $map = $('<div/>', { class: 'map' });

		$map.attr('id', id);

		$content.append( $closer ).append( $map );
		$wrap.append( $overlay ).append( $content );
		$block.append( $wrap );

		$body.append( $block );

		var p = 45;

		function getWidth() { // {{{2
			var w = $content.height() * imapRatio[0] / imapRatio[1];
			if (w >= ($block.width() - (p*2))) return false;
			return w;
		} // getWidth() }}}2

		function setContentSize() { // {{{2
			$content.css({
				'width': '',
				'left': '',
			});

			var w = getWidth();
			$content.css({
				'width': ((w) ? (w + 'px') : ''),
				'left': ((w) ? ((($block.width() - w) / 2) + 'px') : ''),
			});

			$map.each(mapResizeHandler);
		} // setContentSize() }}}2

		setContentSize();

		initYaMap(id, x, y, zoom, function (err, map) {
			if (err) throw err;

			$map.data('yamap', map);
		}, {
			zoomSlider: true,
			mapTypeSelect: true,
		});

		$closer.click(function () { // {{{2
			if (closing) return false; else closing = true;
			$(window).off('resize' + bindSuffix);
			$wrap.stop().animate(
				{ opacity: 0 },
				animationSpeed,
				getVal('animationCurve'), function () {
					$block.remove();
					$wrap.remove();
					$content.remove();
					$overlay.remove();
					$map.remove();
					$closer.remove();
					$html.removeClass('shops_page_big_map');
				});
			return false;
		}); // $closer.click handler }}}2

		$wrap.stop().animate(
			{ opacity: 1 },
			animationSpeed,
			getVal('animationCurve'));

		$(window).on('resize' + bindSuffix,
			$.proxy(setTimeout, null, setContentSize, 0));
	} // openBigMap() }}}1

	$main.each(function (n) { // {{{1
		var $main = $(this);
		var $list = $main.find('>ul.shops_list');
		var $listItems = $list.find('>li');
		var $photos = $listItems.find('.photos');
		var $rightCol = $photos.find('.right_col');
		var $rInterval = $rightCol.find('>*+*');
		var $imaps = $rightCol.find('.imap');
		var $address = $listItems.find('address');

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

			$imaps.each(mapResizeHandler);
		}, 0); // resizeHandler() }}}2

		$imaps.each(function (i) { // {{{2
			var $map = $(this);
			var id = 'interactive_yandex_map_n_'+ n +'_'+ i;
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
			$map.css('cursor', 'pointer').click(function () {
				openBigMap(
					parseFloat($map.attr('data-coord-x')),
					parseFloat($map.attr('data-coord-y')),
					parseInt($map.attr('data-zoom'), 10));
				return false;
			});
		}); // $imaps }}}2

		$address.css('cursor', 'pointer').click(function () {
			$(this).closest('li').find('.imap').eq(0).trigger('click');
			return false;
		});

		$(window).on('resize' + bindSuffix, resizeHandler);
		resizeHandler();
	}); // $main }}}1

}); // ready()
