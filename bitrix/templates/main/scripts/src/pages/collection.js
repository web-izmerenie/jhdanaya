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
	var $list = $('ul.collection_list');
	var $d = $(document);
	var $w = $(window);

	$list.each(function () {
		var $ul = $(this);
		var $liArr = $ul.find('>li');

		$liArr.each(function () {
			var $li = $(this);
			var $info = $li.find('.info');
			var $a = $li.find('a.preview');

			$info
				.css('opacity', 0)
				.prepend('<a class="closer"></a>')
				.prepend('<a class="zoom"></a>');

			function closeHandler() {
				$info.stop().animate({
					'opacity': 0,
				}, getVal('animationSpeed'), function () {
					$(this).css('display', 'none');
					$ul.removeClass('popup');
					$li.removeClass('popup');
				});
				return false;
			}

			$info.find('.closer').click(closeHandler);

			$a.click(function () {
				if ($ul.hasClass('popup')) return false;

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
			});
		});
	});

	$d.on('click' + bindSuffix, function (event) {
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
	});

}); // require() for page passed

}); // stylesReady()
}); // define()
