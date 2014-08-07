/**
 * "collection" page behavior
 *
 * @author Viacheslav Lotsmanov
 */

define(['jquery', 'styles_ready'],
function ($, stylesReady) {
stylesReady(function () {

var $html = $('html');
if (!$html.hasClass('collection_page')) return;
require(['get_val', 'get_local_text', 'relative_number'],
function (getVal, getLocalText, relativeNumber) {

	// values
	var bindSuffix = '.collection_page_bind';

	// dom elements
	var $main = $('main');
	var $footer = $('footer');
	var $list = $main.find('ul.collection_list');
	var $liArr;
	var $previews;
	var $infos;
	var $zooms;
	var $more = $main.find('.load_more');
	var $d = $(document);
	var $w = $(window);

	function brandDetailPageInit() {
		var $block = $('.about_brand');
		if ($block.size() <= 0) return;

		//alert('OH YEAH!');
	}

	if ($list.size() <= 0) {
		brandDetailPageInit();
		return;
	}

	function initList() {
		$liArr = $list.find('>li');
		$previews = $liArr.find('.preview');
		$infos = $liArr.find('.info');
		$zooms = $liArr.find('.zoom');
	}
	initList();

	function liInitHandler() { // {{{1
		var $ul = $list;
		var $li = $(this);
		var $info = $li.find('.info');
		var $a = $li.find('a.preview');
		var $preview = $li.find('.preview');
		var $img = $preview.find('img');

		var imgSrc = $img.attr('src');

		// loadBlur {{{2
		if ($ul.hasClass('brand')) {
			function loadBlur() {
				require(['blur_img', 'load_img'],
				function (blurImg, loadImg) {
					blurImg({
						src: imgSrc,
						radius: 10,
					}, function (err, dataURL) {
						if (err) {
							if (err instanceof loadImg.exceptions.Timeout) {
								setTimeout(loadBlur, 1); // try again
							} else window.console.error(err);
							return;
						}

						var $newImg = $('<img>', {
							'alt': '',
							'src': dataURL,
							'class': 'blur',
						});
						$preview.append( $newImg );
					});
				});
			}
			setTimeout(loadBlur, 1);
		}
		// loadBlur }}}2

		if ($ul.hasClass('brand')) {
			$info
				.css('opacity', 0)
				.prepend('<a class="closer"></a>')
				.prepend('<a class="zoom"></a>');

			function closeHandler() { // {{{2
				$info.animate({
					'opacity': 0,
				}, getVal('animationSpeed'), getVal('animationCurve'), function () {
					$(this).css('display', 'none');
					$ul.removeClass('popup');
					$li.removeClass('popup');
				});
				return false;
			} // closeHandler() }}}2

			$info.find('.closer').click(closeHandler);
			$info.find('.zoom').click(function () {
				alert('Поведение кнопки не определено.');
				return false;
			});

			$a.click(function () { // {{{2
				if ($ul.hasClass('popup')) {
					$ul.find('>li.popup .info .closer').trigger('click');
					return false;
				}

				$ul.addClass('popup');
				$li.addClass('popup');
				$info.css('display', 'block');
				$info.css('top', (
					$d.scrollTop() +
					($w.height() / 2) -
					($info.innerHeight() / 2)
				) + 'px');
				$info.stop().animate({
					'opacity': 1,
				}, getVal('animationSpeed'), getVal('animationCurve'));
				return false;
			}); // open popup }}}2
		} else if ($ul.hasClass('rings')) {
			$info.append('<a class="zoom"><span></span></a>');
			$info.find('.zoom').click(function () {
				alert('Поведение кнопки не определено.');
				return false;
			});
		}

		initList();
	} // liInitHandler() }}}1

	$liArr.each(liInitHandler);

	if ($list.hasClass('brand')) {
		$d.on('click' + bindSuffix, function (event) { // {{{1
			var $infoOpened = $list.find('>li.popup .info');
			if ($infoOpened.size() <= 0) return true;

			var x = $infoOpened.offset().left;
			var y = $infoOpened.offset().top;
			var w = $infoOpened.innerWidth();
			var h = $infoOpened.innerHeight();

			// hell IE
			if (event.pageX < 0 || event.pageY < 0) return true;

			if (
				!(event.pageX >= x && event.pageX <= x+w) ||
				!(event.pageY >= y && event.pageY <= y+h)
			) {
				$infoOpened.find('.closer').trigger('click');
				return false;
			}

			return true;
		}); // $d.click }}}1
	}

	// relative size {{{1

		var relativeSizeBindSuffix = '.collection_page_relative_size_bind';
		var moreSizeMin = getVal('circleDownArrowButtonSizeMin');
		var moreSizeMax = getVal('circleDownArrowButtonSizeMax');
		var itemSizeMin = 208;
		var itemSizeMax = 342;
		var rMinW = getVal('minWidth');
		var rMaxW = getVal('maxWidth');
		var topMin = 75;
		var topMax = 130;

		if ($list.hasClass('rings')) {
			itemSizeMin = 170;
			itemSizeMax = 272;
			topMin = 50;
			topMax = 80;

			var ringItemSizeMin = 277;
			var ringItemSizeMax = 450;
			var zoomHeightMin = 49;
			var zoomHeightMax = 79;
		} else if ($list.hasClass('produce')) {
			itemSizeMin = 232;
			itemSizeMax = 339;
			topMin = 54;
			topMax = 90;

			var fontSizeMin = 22;
			var fontSizeMax = 31;
			var textWidthMin = 277;
			var textWidthMax = 460;

			var $a = $liArr.find('>a');
			var $pic = $a.find('>span:first');
			var $font = $a.find('>span:first + span');
		}

		var $relMore = $main.find('ul.collection_list + .load_more');

		function setRelSizes() { // {{{2
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

			var size;
			var top = rn(topMin, topMax);

			size = rn(moreSizeMin, moreSizeMax);
			$more.css({
				width: size + 'px',
				height: size + 'px',
				'border-radius': (size / 2) + 'px',
			});

			size = rn(itemSizeMin, itemSizeMax);

			$liArr.css('margin-top', top + 'px');

			if ($list.hasClass('rings')) {
				var iSize = rn(ringItemSizeMin, ringItemSizeMax);
				$liArr.css('height', iSize + 'px');
				$infos.css('width', iSize + 'px');
				$infos.css('margin-left', (($liArr.eq(0).width() - iSize) / 2) + 'px');
				$zooms.css('height', rn(zoomHeightMin, zoomHeightMax) + 'px');
			} else if ($list.hasClass('brand')) {
				$liArr.css('height', size + 'px');
			} else if ($list.hasClass('produce')) {
				$a.css('width', rn(textWidthMin, textWidthMax) + 'px');
				$pic.css({
					'width': size + 'px',
					'height': size + 'px',
				});
				$font.css({
					'width': rn(textWidthMin, textWidthMax) + 'px',
					'font-size': rn(fontSizeMin, fontSizeMax) + 'px',
					'line-height': (rn(fontSizeMin, fontSizeMax) + 2) + 'px',
				});
				return;
			}

			$previews.css({
				'width': size + 'px',
				'height': size + 'px',
			});
			$relMore.css('margin-top', top + 'px');
		} // setRelSizes() }}}2

		$w.on('resize' + relativeSizeBindSuffix, $.proxy(setTimeout, null, function () {
			// reset
			$more.css({
				width: '',
				height: '',
				'border-radius': '',
			});
			$liArr.css({
				'width': '',
				'height': '',
				'margin-top': '',
			});
			$previews.css({
				'width': '',
				'height': '',
			});
			$infos.css({
				'width': '',
				'height': '',
				'margin-left': '',
			});
			if ($list.hasClass('produce')) {
				$a.css({
					'width': '',
					'height': '',
				});
				$pic.css({
					'width': '',
					'height': '',
				});
				$font.css({
					'width': '',
					'height': '',
					'font-size': '',
					'line-height': '',
				});
			}
			$zooms.css({
				'width': '',
				'height': '',
			});
			$relMore.css('margin-top', '');

			setRelSizes();
		}, 1));

		$w.trigger('resize' + relativeSizeBindSuffix);

	// relative size }}}1

	$more.on('click' + bindSuffix, function () { // {{{1
		if ($more.data('process')) return false;
		$more.data('process', true)
		$more.addClass('loading');

		setTimeout(function () {

			function stopping() {
				$more.removeClass('loading');
				setTimeout(function () {
					$more.removeData('process');
					$w.trigger('scroll' + bindSuffix);
				}, getVal('animationSpeed'));
			}

			var getData = {
				page: parseInt($more.attr("data-next-page"), 10),
				iblock: $more.attr('data-iblock'),
			};

			if ($more.attr('data-count'))
				getData.count = parseInt($more.attr('data-count'), 10);

			$.ajax({ // {{{2
				url: getVal('dynamicItemsLoadURL'),
				type: 'GET',
				cache: false,
				dataType: 'text',
				data: getData,
				success: function (data) {
					require(['json_answer'], function (jsonAnswer) {
						jsonAnswer.validate(data, function (err, json) {
							$more.attr("data-next-page", ++(getData.page));

							if (err) {
								if (
									err instanceof jsonAnswer.exceptions.UnknownStatusValue &&
									err.json && err.json.status === 'end_of_list'
								) {
									$list.addClass('end_of_list');
									$more.slideUp(getVal('animationSpeed') * 6, function () {
										$more.remove();
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

							var items = json.items;
							var i = 0;

							var loopEnd = stopping;

							function loadItemLoop() { // {{{3
								if (items.length <= i) return loopEnd();

								var item = items[i++];
								var imgTag;

								var $newLi = $('<li/>');
								if (item.id) $newLi.attr('id', item.id);

								// preview {{{4

								if (
									!$.isPlainObject( item.preview ) ||
									$.type(item.preview.src) !== 'string'
								) {
									alert(getLocalText('ERR', 'AJAX_PARSE'));
									return stopping();
								}

								var $preview;
								if (item.info) $preview = $('<a/>');
								else $preview = $('<span/>');
								$preview.addClass('preview');

								imgTag = '<img alt="';
								if (item.preview.description)
									imgTag += item.preview.description;
								imgTag += '" src="'+ item.preview.src +'"';
								if (item.preview.width)
									imgTag += ' width="'+ item.preview.width +'"';
								if (item.preview.height)
									imgTag += ' height="'+ item.preview.height +'"';
								imgTag += ' />';

								$preview.html( imgTag );
								$newLi.append( $preview );

								// preview }}}4

								// info {{{4
								var $info = '';
								if (item.info) {
									$info = $('<div class="info" />');

									if ($list.hasClass('brand')) {
										if (
											$.type(item.info.text) !== 'string' ||
											!$.isPlainObject(item.info.picture) ||
											$.type(item.info.picture.src) !== 'string'
										) {
											alert(getLocalText('ERR', 'AJAX_PARSE'));
											return stopping();
										}

										var $text = $('<div class="text" />');
										$text.html(item.info.text);
										$info.append( $text );

										imgTag = '<img class="picture" alt="';
										if (item.info.picture.description)
											imgTag += item.info.picture.description;
										imgTag += '" src="'+ item.info.picture.src +'"';
										if (item.info.picture.width)
											imgTag += ' width="'+ item.info.picture.width +'"';
										if (item.info.picture.height)
											imgTag += ' height="'+ item.info.picture.height +'"';
										imgTag += ' />';

										$info.append(imgTag);
									} else if ($list.hasClass('rings')) {
										if ($.type(item.info.text) === 'string') {
											var $text = $('<div class="text" />');
											$text.html(item.info.text);
											$info.append( $text );
										}
									}

									$newLi.append( $info );
								}
								// info }}}4

								$newLi.css('opacity', 0);
								$newLi.each(liInitHandler);
								$liArr.last().after( $newLi );
								initList();
								setRelSizes();

								setTimeout(function () {
									var $el = $liArr.last();
									$el.animate(
										{ opacity: 1 },
										getVal('animationSpeed'),
										getVal('animationCurve'),
										loadItemLoop
									);
								}, 1);
							} // loadItemLoop() }}}3

							loadItemLoop();
						}); // jsonAnswer.validate()
					}); // require(['json_answer']...
				}, // "success"
				error: function () {
					alert(getLocalText('ERR', 'AJAX'));
					stopping();
				}
			}); // .ajax() }}}2

		}, getVal('animationSpeed')); // setTimeout()

		return false;
	}); // $more.click }}}1

	if ($more.size() > 0) { // {{{1
		$w.on('scroll' + bindSuffix, function () {
			if ($d.scrollTop() + $w.height() >= $more.offset().top) {
				$more.trigger('click' + bindSuffix);
			}
		});
	} // auto more by scroll }}}1

}); // require() for page passed

}); // stylesReady()
}); // define()
