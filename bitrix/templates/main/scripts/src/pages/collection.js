/**
 * "collection" pages behavior
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

	if (!$html.hasClass('collection_page')) return;

	var getVal = basics.getVal;
	var getLocalText = basics.getLocalText;
	var relativeNumber = require('../basics/relative_number');

	// values
	var bindSuffix = '.collection_page_bind';
	var skipDetailInfoBlock = false; // go to detail picture directly
	var previewHoverAdd = getVal('collectionsPreviewHoverAdd');

	// dom elements
	var $main = $('main');
	var $footer = $('footer');
	var $list = $main.find('ul.collection_list');
	var $liArr;
	var $previews;
	var $infosH; // hover
	var $infosD; // detail
	var $zoomH;
	var $more = $main.find('.load_more');
	var $d = $(document);
	var $w = $(window);

	var alert = window.alert;

	function brandDetailPageInit() {
		var $block = $('.about_brand');
		if ($block.size() <= 0) return;

		$block.find('.youtube_video').each(function () {
			var attrName = 'data-youtube-id';
			var attr = $(this).attr(attrName);

			if (!attr)
				return require('../just_log_error')(
					new Error('Missed attribute "'+ attrName +'".'));

			$(this).html('<iframe src="//www.youtube.com/embed/'+
				attr +'?rel=0" allowfullscreen></iframe>');
		});
	}

	if ($list.size() <= 0) {
		brandDetailPageInit();
		return;
	}

	function initList() {
		$liArr = $list.find('>li');
		$previews = $liArr.find('.preview');
		$infosH = $liArr.find('.info.hover');
		$infosD = $liArr.find('.info.detail');
		$zoomH = $infosH.find('.zoom');
	}
	initList();

	function initSizeAnimationHack() { // {{{1
		if (!$list.data('preview-percent')) return;
		$list.find('>li .preview img').each(function () {
			var $img = $(this);

			if ($img.hasClass('hack_inited'))
				return;
			else
				$img.addClass('hack_inited');

			$img
				.css('background-image', "url('"+ $img.attr('src') +"')")
				.attr('src', getVal('blankTransparentPixelURL'))
				.css(
					'background-size',
					parseFloat($list.data('preview-percent')) + '% auto');
		});
	} // initSizeAnimationHack() }}}1

	function liInitHandler() { // {{{1
		var $ul = $list;
		var $li = $(this);
		var $infoH = $li.find('.info.hover');
		var $infoD = $li.find('.info.detail');
		var $dText = $infoD.find('.text');
		var $a = $li.find('>a.preview');
		var $aJustLink = $li.find('>a');
		var $preview = $li.find('>.preview');
		var $img = $preview.find('img');

		if ($ul.hasClass('products') && $aJustLink.attr('href')) {
			var $p = $('<p/>', { class: 'go-detail' });
			var $goDetailA = $('<a/>', { href: $aJustLink.attr('href') });
			$goDetailA.html( basics.getLocalText('DETAIL') );
			$p.append($goDetailA);
			$dText.append($p);
		}

		var imgSrc = $img.attr('src');

		var loadBlur, closeHandler;
		var $infoDCloser, $infoDZoom;

		// previews animation {{{2

		function previewOver() {
			var size = $list.data('previews-size');
			var percent = $list.data('preview-percent');
			if (!size || !percent) return false;
			size = parseFloat(size);
			percent = parseFloat(percent);

			$preview.find('img')
				.css('background-size', '100% auto');
			return false;
		}

		function previewOut() {
			var size = $list.data('previews-size');
			var percent = $list.data('preview-percent');
			if (!size || !percent) return false;
			size = parseFloat(size);
			percent = parseFloat(percent);

			$preview.find('img')
				.css('background-size', percent + '% auto');
			return false;
		}

		// previews animation }}}2

		// loadBlur {{{2
		if ($ul.hasClass('brand') || $ul.hasClass('rings')) {
			loadBlur = function () {
				var LoadImg = require('../basics/load_img');
				var blurImg = require('../basics/blur_img');
				blurImg({
					src: imgSrc,
					radius: 10,
				}, function (err, dataURL) {
					if (err) {
						if (err instanceof LoadImg.exceptions.Timeout)
							setTimeout(loadBlur, 0); // try again
						else
							window.console.error(err);

						return;
					}

					var $newImg = $('<img>', {
						'alt': '',
						'src': dataURL,
						'class': 'blur',
					});
					$preview.append( $newImg );

					initSizeAnimationHack();
				});
			}; // loadBlur()
			setTimeout(loadBlur, 0);
		}
		// loadBlur }}}2

		if ( // {{{2
			$ul.hasClass('brand') || $ul.hasClass('rings')
		) {
			$infoD
				.css('opacity', 0)
				.prepend('<a class="closer" href="javascript:void(\'close\');"></a>')
				.prepend('<a class="zoom" href="javascript:void(\'zoom\');"></a>');

			closeHandler = function () { // {{{3
				$infoD.animate({
					'opacity': 0,
				}, getVal('animationSpeed'), getVal('animationCurve'), function () {
					$(this).css('display', 'none');
					$ul.removeClass('popup');
					$li.removeClass('popup');
					$html.removeClass('collection_page_over_popup');
				});
				return false;
			}; // closeHandler() }}}3

			$infoDCloser = $infoD.find('.closer');
			$infoDZoom = $infoD.find('.zoom');

			$infoDCloser.click(closeHandler);
			$infoDZoom.click(function () {

				var process = false;

				$html.addClass('collection_page_big_photo');
				$ul.animate(
					{ 'opacity': 0 },
					getVal('animationSpeed'),
					getVal('animationCurve'),
					function () {
						$(this).css('visibility', 'hidden');
					});

				var $block = $('<div/>').addClass('collection_page_big_photo_block');
				var $closer = $('<a/>').addClass('closer');
				var $wrap = $('<div/>').addClass('wrap');
				var $img = $('<img/>');

				if ($ul.hasClass('products')) {
					$img.attr('src', $infoD.find('img.picture').attr('src'));
				} else {
					$img.attr('src', $a.attr('href'));
				}

				$wrap.append($img);
				$block.append($closer).append($wrap);

				$('body').append($block);

				$block.stop().animate(
					{opacity: 1},
					getVal('animationSpeed'),
					getVal('animationCurve'), function () {
						// show big photo {{{3

						$img.on('click', function () { return false; });

						$closer.on('click', function () {
							if (process) return false; else process = true;

							$block.animate(
								{ opacity: 0 },
								getVal('animationSpeed'),
								getVal('animationCurve'), function () {
									$html.removeClass('collection_page_big_photo');
									$block.remove();
								});

							$ul.stop().css('visibility', 'visible').animate(
								{ 'opacity': 1 },
								getVal('animationSpeed'),
								getVal('animationCurve'));

							// skip back to detail info
							if (skipDetailInfoBlock)
								$infoDCloser.trigger('click');

							return false;
						});

						$block.on('click', function () {
							$closer.trigger('click');
							return false;
						});

						// show big photo }}}3
					});

				return false;
			});

			$li.data('info_detail', $infoD);
			$infoD.addClass('collection_info_detail');
			$infoD.appendTo('body');

			$aJustLink.click(function () { // {{{3
				if ($ul.hasClass('popup')) {
					if ($html.hasClass('collection_page_big_photo')) return false;

					var $cur = $ul.find('>li.popup');
					if ($cur.size() <= 0) throw new Error('OH SHI~');
					$cur = $cur.data('info_detail');

					if (!$cur) throw new Error('OH SHI~');
					if (!($cur instanceof $)) throw new Error('OH SHI~');
					if ($cur.size() <= 0) throw new Error('OH SHI~');

					$cur.find('.closer').trigger('click');

					return false;
				}

				$html.addClass('collection_page_over_popup');
				$ul.addClass('popup');
				$li.addClass('popup');
				$infoD.css('display', 'block');
				$infoD.stop().animate(
					{ 'opacity': 1 },
					getVal('animationSpeed'),
					getVal('animationCurve'));
				return false;
			}); // open popup }}}3

			$infoH.append('<a class="zoom"><span></span></a>');

			if (skipDetailInfoBlock) {
				// open big photo (without detail info)
				$infoH.click(function () {
					$html.addClass('collection_page_over_popup');
					$ul.addClass('popup');
					$li.addClass('popup');
					$infoDZoom.trigger('click');
					return false;
				});
			} else {
				// open detail info block
				$infoH.click(function () {
					$aJustLink.trigger('click');
					return false;
				});
			}

			// zoom when mouse over
			$infoH.hover(previewOver, previewOut);
		} // if ul.rings or (ul.brands and not ul.products) }}}2

		if ($ul.hasClass('rings') && $ul.hasClass('products')) {
			// zoom when mouse over
			$aJustLink.hover(previewOver, previewOut);
		}

		initList();
	} // liInitHandler() }}}1

	$liArr.each(liInitHandler);

	if ( // {{{1
		$list.hasClass('brand') || $list.hasClass('rings')
	) {
		$d.on('click' + bindSuffix, function (event) {
			if ($html.hasClass('collection_page_big_photo')) return true;

			var $infoOpened = $list.find('>li.popup');
			if ($infoOpened.size() <= 0) return true;
			$infoOpened = $infoOpened.data('info_detail');

			if (!$infoOpened) throw new Error('OH SHI~');
			if (!($infoOpened instanceof $)) throw new Error('OH SHI~');
			if ($infoOpened.size() <= 0) throw new Error('OH SHI~');

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
		});
	} // $d.click }}}1

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

	// "rings" preview photos smaller than "brand" preview photos
	/*if ($list.hasClass('rings')) {
		itemSizeMin = 170;
		itemSizeMax = 272;
	}*/

	if ($list.hasClass('rings') || $list.hasClass('brand')) {
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

	var $pagiBottom = $main.find('.pagination.bottom');

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

		if ($list.hasClass('rings') || $list.hasClass('brand')) {
			var iSize = rn(ringItemSizeMin, ringItemSizeMax);
			$infosH.css('width', iSize + 'px');
			$infosH.css('margin-left', (($liArr.eq(0).width() - iSize) / 2) + 'px');
			$zoomH.css('height', rn(zoomHeightMin, zoomHeightMax) + 'px');
			$liArr.css('height', iSize + 'px');
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

		if ($list.hasClass('rings') || $list.hasClass('brand')) {
			size += previewHoverAdd;
			$list
				.data('previews-size', size)
				.data('preview-percent', (size - previewHoverAdd) * 100 / size);
			$previews.css({
				'width': size + 'px',
				'height': size + 'px',
			});
			initSizeAnimationHack();
		} else {
			$previews.css({
				'width': size + 'px',
				'height': size + 'px',
			});
		}
		$relMore.css('margin-top', top + 'px');

		if ($list.hasClass('rings') && $list.hasClass('products')) {
			$pagiBottom.css('margin-top', top + 'px');
		}
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
		$pagiBottom.css('margin-top', '');
		$previews.stop().css({
			'width': '',
			'height': '',
		});
		$infosH.css({
			'width': '',
			'height': '',
			'margin-left': '',
		});
		$infosD.css({
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
		$zoomH.css({
			'width': '',
			'height': '',
		});
		$relMore.css('margin-top', '');

		setRelSizes();
	}, 0));

	$w.trigger('resize' + relativeSizeBindSuffix);

	// relative size }}}1

	if (!($list.hasClass('rings') && $list.hasClass('products'))) {
		$more.on('click' + bindSuffix, function () { // {{{1
			if ($more.data('process')) return false;
			$more.data('process', true);
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
					section: $more.attr('data-iblock-section'),
					brand: $more.attr('data-brand'),
					datafor: $more.attr('data-for'),
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
						var jsonAnswer = require('../basics/json_answer');
						jsonAnswer.validate(data, function (err, json) {
							if (err) {
								if (
									err instanceof jsonAnswer.exceptions.UnknownStatusValue &&
									err.json && err.json.status === 'end_of_list'
								) {
									$list.addClass('end_of_list');
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
								if (item.info_detail) {
									$preview = $('<a/>');
									if (item.info_detail.picture && item.info_detail.picture.src)
										$preview.attr('href', item.info_detail.picture.src);
								} else $preview = $('<span/>');
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
								if ($list.hasClass('brand') || $list.hasClass('rings')) {
									if (item.info_detail) {
										$info = $('<div class="info detail" />');

										if (
											$.type(item.info_detail.text) !== 'string' ||
											!$.isPlainObject(item.info_detail.picture) ||
											$.type(item.info_detail.picture.src) !== 'string'
										) {
											alert(getLocalText('ERR', 'AJAX_PARSE'));
											return stopping();
										}

										var $text = $('<div class="text" />');
										$text.html(item.info_detail.text);
										$info.append( $text );

										imgTag = '<img class="picture" alt="';
										if (item.info_detail.picture.description)
											imgTag += item.info_detail.picture.description;
										imgTag += '" src="'+ item.info_detail.picture.src +'"';
										if (item.info_detail.picture.width)
											imgTag += ' width="'+ item.info_detail.picture.width +'"';
										if (item.info_detail.picture.height)
											imgTag += ' height="'+ item.info_detail.picture.height +'"';
										imgTag += ' />';

										$info.append(imgTag);
										$newLi.append( $info );
										$info = '';
									} // if info detail

									if (item.info_hover) {
										$info = $('<div class="info hover" />');

										if ($.type(item.info_hover.text) === 'string') {
											var $text2 = $('<div class="text" />');
											$text2.html(item.info_hover.text);
											$info.append( $text2 );
										}

										$newLi.append( $info );
										$info = '';
									} // if info hover
								} // if brands or rings
								$info = undefined;
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
			var offset = $more.offset();
			if (!offset || !('top' in offset)) return;
			if ($d.scrollTop() + $w.height() >= offset.top)
				$more.trigger('click' + bindSuffix);
		});
	}

}); // ready()
