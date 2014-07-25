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
		relativeMaxWidth: 2560, // px (for main page)
		animationSpeed: 200, // ms
		animationCurve: 'easeInOutQuad', // easing
		fixedHeaderHTMLClass: 'fixed_header',
		headerSmallLogoURL: 'header_small_logo.png',
		footerHeight: 74, // px
		waiterTimeout: 200, // ms
		mainPageSliderInterval: 3, // sec

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
