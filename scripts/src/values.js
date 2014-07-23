/**
 * Values for "get_val" module
 *
 * @author Viacheslav Lotsmanov
 */

define(function () {

	/** @public */ var exports = {};

	exports.values = {

		minWidth: 980, // px
		maxWidth: 1600, // px
		animationSpeed: 200, // ms
		fixedHeaderHTMLClass: 'fixed_header',
		headerSmallLogoURL: 'header_small_logo.png',

	};

	/** Required set before "getVal" */
	exports.required = [
		'clientSide',
		'lang',
		'revision',
		'tplPath',
	];

	return exports;

}); // define()
