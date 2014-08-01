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
require(['get_val', 'relative_number'], function (getVal, relativeNumber) {

	// values
	var minW = getVal('minWidth');
	var maxW = getVal('maxWidth');
	var bindSuffix = '.collection_page_bind';

	// dom elements
	var $main = $('main');
	var $footer = $('footer');
	var $list = $main.find('ul.collection_list');
	var $liArr = $list.find('>li');
	var $previews = $liArr.find('.preview');
	var $more = $main.find('.load_more');
	var $d = $(document);
	var $w = $(window);

	$list.each(function () { // {{{1
		var $ul = $(this);
		var $liArr = $ul.find('>li');

		$liArr.each(function () {
			var $li = $(this);
			var $info = $li.find('.info');
			var $a = $li.find('a.preview');
			var $preview = $li.find('.preview');
			var $img = $preview.find('img');

			var imgSrc = $img.attr('src');

			// loadGray {{{2
			function loadGray() {
				require(['grayscale_img', 'load_img'],
				function (grayscaleImg, loadImg) {
					grayscaleImg(imgSrc, function (err, dataURL) {
						if (err) {
							if (err instanceof loadImg.exceptions.Timeout) {
								setTimeout(loadGray, 1); // try again
							} else window.console.error(err);
							return;
						}

						var $newImg = $('<img>', {
							'alt': '',
							'src': dataURL,
							'class': 'grayscale',
						});
						$preview.append( $newImg );
					});
				});
			}
			setTimeout(loadGray, 1);
			// loadGray }}}2

			// loadBlur {{{2
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
			// loadBlur }}}2

			$info
				.css('opacity', 0)
				.prepend('<a class="closer"></a>')
				.prepend('<a class="zoom"></a>');

			function closeHandler() {
				$info.animate({
					'opacity': 0,
				}, getVal('animationSpeed'), function () {
					$(this).css('display', 'none');
					$ul.removeClass('popup');
					$li.removeClass('popup');
				});
				return false;
			}

			$info.find('.closer').click(closeHandler);
			$info.find('.zoom').click(function () {
				alert('Поведение кнопки не определено дизайном.');
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
				}, getVal('animationSpeed'));
				return false;
			}); // open popup }}}2
		}); // $liArr
	}); // $list }}}1

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

		var $relMore = $main.find('ul.collection_list + .load_more');

		$w.on('resize' + relativeSizeBindSuffix, $.proxy(setTimeout, null, function () {

			var w;

			function rn(min, max) {
				return relativeNumber({
					relVal: w,
					relMin: rMinW,
					relMax: rMaxW,
					min: min,
					max: max,
				});
			} // rn()

			// reset
			$more.css({
				width: '',
				height: '',
				'border-radius': '',
			});
			$liArr.css({
				'height': '',
				'margin-top': '',
			});
			$previews.css({
				'width': '',
				'height': '',
			});
			$relMore.css('margin-top', '');

			w = $footer.width();

			var size;
			var top = rn(topMin, topMax);

			size = rn(moreSizeMin, moreSizeMax);
			$more.css({
				width: size + 'px',
				height: size + 'px',
				'border-radius': (size / 2) + 'px',
			});

			size = rn(itemSizeMin, itemSizeMax);
			$liArr.css({
				'height': size + 'px',
				'margin-top': top + 'px',
			});
			$previews.css({
				'width': size + 'px',
				'height': size + 'px',
			});
			$relMore.css('margin-top', top + 'px');

		}, 1));

		$w.trigger('resize' + relativeSizeBindSuffix);

	// relative size }}}1

}); // require() for page passed

}); // stylesReady()
}); // define()
