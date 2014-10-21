/**
 * "events" pages behavior
 *
 * @author Viacheslav Lotsmanov
 * @license GNU/AGPLv3
 * @see {@link https://github.com/web-izmerenie/jhdanaya/blob/master/LICENSE-AGPLv3|License}
 */

var $ = require('jquery');
var ready = require('../ready');
var basics = require('../basics');

ready(function (window, document, undefined) {

	var $html = $('html');
	var $eventsList = $('.events_list');

	if ($eventsList.size() <= 0 && !$html.hasClass('event_detail_page')) return;

	var getVal = basics.getVal;
	var getLocalText = basics.getLocalText;

	var $photos = $('html.event_detail_page body main section.event_detail');
	$photos = $photos.find('ul.preview_photos>li a');

	if ($photos.size() > 0) {
		require('jquery.colorbox');
		$photos.colorbox(getVal('galleryColorboxParams'));
	}

	// only for list page

	if ($html.hasClass('event_detail_page')) return;

	var alert = window.alert;
	var relativeNumber = require('../basics/relative_number');

	var $section = $('section.events_list');
	var $more = $section.find('.load_more');
	var $els;
	var $d = $(document);
	var $w = $(window);
	var $footer = $('footer');

	var bindSuffix = '.events_list_page';

	function reInit() {
		$els = $section.find('.event_item');
	}

	reInit();

	// relative sizes {{{1

	var rMinW = getVal('minWidth');
	var rMaxW = getVal('maxWidth');
	var moreSizeMin = getVal('circleDownArrowButtonSizeMin');
	var moreSizeMax = getVal('circleDownArrowButtonSizeMax');
	var relSizeBindSuffix = '.events_list_page_relative_sizes';

	function relSize() {
		var w = $footer.width();

		function rn(min, max) {
			return relativeNumber({
				relVal: w,
				relMin: rMinW,
				relMax: rMaxW,
				min: min,
				max: max,
			});
		} // rn()

		var size = rn(moreSizeMin, moreSizeMax);
		$more.css({
			width: size + 'px',
			height: size + 'px',
			'border-radius': (size / 2) + 'px',
		});
	}

	$w.on('resize' + relSizeBindSuffix, $.proxy(setTimeout, null, function () {
		$more.css({
			width: '',
			height: '',
			'border-radius': '',
		});

		relSize();
	}, 0));

	$w.trigger('resize' + relSizeBindSuffix);

	// relative sizes }}}1

	$more.on('click', function () { // {{{1
		if ($more.data('process')) return false;
		$more.data('process', true);
		$more.addClass('loading');

		setTimeout(function () {

			function stopping() {
				$more.removeClass('loading');
				setTimeout(function () {
					$more.removeData('process');
				}, getVal('animationSpeed'));
			}

			var getData = {
				page: parseInt($more.attr("data-next-page"), 10),
			};

			if ($more.attr('data-count'))
				getData.count = parseInt($more.attr('data-count'), 10);

			$.ajax({ // {{{2
				url: getVal('moreEventsAjaxURL'),
				type: 'GET',
				cache: false,
				dataType: 'text',
				data: getData,
				success: function (data) {
					var jsonAnswer = require('../basics/json_answer');
					jsonAnswer.validate(data, function (err, json) {
						if (err) {
							if (
								err instanceof jsonAnswer.exceptions.UnknownStatusValue &&
								err.json && err.json.status === 'end_of_list'
							) {
								$more.slideUp(getVal('animationSpeed') * 6, function () {
									$more.remove();
									$w.off('scroll' + bindSuffix);
								});
								if (!err.json.items) return;
								else json = err.json;
							} else {
								alert(getLocalText('ERR', 'AJAX') + '\n\n' + err.toString());
								return stopping();
							}
						}

						if (!$.isArray(json.items)) {
							alert(getLocalText('ERR', 'AJAX_PARSE'));
							return stopping();
						}

						$more.attr("data-next-page", ++(getData.page));

						var items = json.items;
						var i = 0;

						var loopEnd = stopping;

						function loadItemLoop() { // {{{3
							if (items.length <= i) return loopEnd();

							var item = items[i++];

							var $newEl = $('<div/>', { class: 'event_item' });
							if (item.id) $newEl.attr('id', item.id);
							$newEl.css('opacity', '0');

							var $h3 = $('<h3/>');
							var $title = $('<span/>').html(item.title);
							var $link;
							if (item.link) {
								$link = $('<a/>', { href: item.link });
								$link.append($title);
								$h3.append($link);
							} else {
								$h3.append($title);
							}
							$newEl.append($h3);

							var $date = $('<h4/>').html(item.date);
							$newEl.append($date);

							var $previewText;
							if (item.preview_text) {
								$previewText = $('<div/>', { class: 'preview_text' });
								$previewText.html(item.preview_text);
								$newEl.append($previewText);
							}

							var $previewPhotos;
							if (
								$.isArray(item.preview_photos) &&
								item.preview_photos.length > 0
							) {
								$previewPhotos = $('<ul/>', { class: 'preview_photos' });
								$.each(item.preview_photos, function (i, item) {
									var $li = $('<li/>');
									var $img = $('<img/>');
									if (item.description) $img.attr('alt', item.description);
									$img.attr('src', item.src);
									if (item.width) $img.attr('width', item.width);
									if (item.height) $img.attr('height', item.height);
									$li.append($img);
									$previewPhotos.append($li);
								});
								$newEl.append($previewPhotos);
							}

							$els.last().after( $newEl );

							reInit();

							setTimeout(function () {
								var $el = $els.last();
								$el.animate(
									{ opacity: 1 },
									getVal('animationSpeed'),
									getVal('animationCurve'),
									loadItemLoop
								);
							}, 0);
						} // loadItemLoop() }}}3

						loadItemLoop();
					}); // jsonAnswer.validate()
				}, // "success"
				error: function () {
					alert(getLocalText('ERR', 'AJAX'));
					stopping();
				}
			}); // .ajax() }}}2

		}, getVal('animationSpeed')); // setTimeout()

		return false;
	}); // $more.click }}}1

	$w.on('scroll' + bindSuffix, function () {
		if ($d.scrollTop() + $w.height() >= $more.offset().top)
			$more.trigger('click');
	});

}); // ready()
