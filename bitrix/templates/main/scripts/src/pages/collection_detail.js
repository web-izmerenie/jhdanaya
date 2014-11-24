/**
 * "collection" detail pages behavior
 *
 * @author Viacheslav Lotsmanov
 * @license GNU/AGPLv3
 * @see {@link https://github.com/web-izmerenie/jhdanaya/blob/master/LICENSE-AGPLv3|License}
 */

var $ = require('jquery');
var ready = require('../ready');

ready(function (window, document, undefined) {

	var $html = $('html');

	if (!$html.hasClass('collection_page')) return;

	var $main = $('main');
	var $c = $main.find('.collection_detail');
	var $content = $c.find('.detail_content');
	var $img = $content.find('.picture img');

	var imgSrc = $img.attr('src');

	if (imgSrc) {
		$('<img/>').load(function () {
			$img.css('max-width', this.width + 'px');
		}).attr('src', imgSrc);
	}

});
