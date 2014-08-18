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
		dynamicApiLoadInterval: 200, // ms
		loadImgTimeout: 30000, // ms

		circleDownArrowButtonSizeMin: 64,
		circleDownArrowButtonSizeMax: 96,

		dynamicItemsLoadURL: '/ajax/handler.php',

		galleryColorboxParams: {
			transition: 'fade',
			height: '80%',
			opacity: 0.7,
			rel: 'photos',
		},

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
