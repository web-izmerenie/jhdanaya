/**
 * Values for "get_val" module
 *
 * @author Viacheslav Lotsmanov
 */

module.exports.values = {

	minWidth: 980, // px
	maxWidth: 1600, // px
	relativeMaxWidth: 2560, // px (for main page)
	animationSpeed: 200, // ms
	animationCurve: 'easeInOutQuad', // easing
	fixedHeaderHTMLClass: 'fixed_header',
	headerSmallLogoURL: 'header_small_logo.png',
	footerHeight: 74, // px
	waiterInterval: 200, // ms
	mainPageSliderInterval: 3, // sec
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
module.exports.required = [
	'lang',
	'revision',
	'tplPath',
	'debug',
];
