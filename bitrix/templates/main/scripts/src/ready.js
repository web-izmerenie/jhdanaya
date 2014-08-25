/**
 * DOM-Ready abstract wrapper
 *
 * @author Viacheslav Lotsmanov
 */

var $ = require('jquery'),
	basics = require('./basics');

module.exports = function (factory) {
	basics.init(function () {
		$(function () {
			factory(window, window.document);
		});
	});
};
