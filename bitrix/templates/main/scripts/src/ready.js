/**
 * DOM-Ready abstract wrapper
 *
 * @author Viacheslav Lotsmanov
 * @license GNU/AGPLv3
 * @see {@link https://github.com/web-izmerenie/jhdanaya/blob/master/LICENSE-AGPLv3|License}
 */

var $ = require('jquery');
var basics = require('./basics');

module.exports = function (factory) {
	basics.init(function () {
		$(function () {
			factory(window, window.document);
		});
	});
};
