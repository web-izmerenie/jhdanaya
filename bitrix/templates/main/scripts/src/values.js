/**
 * Values for "get_val" module
 *
 * @author Viacheslav Lotsmanov
 * @license GNU/AGPLv3
 * @see {@link https://github.com/web-izmerenie/jhdanaya/blob/master/LICENSE-AGPLv3|License}
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
	loadImgTimeout: 30, // sec
	collectionsPreviewHoverAdd: 40, // px

	circleDownArrowButtonSizeMin: 64,
	circleDownArrowButtonSizeMax: 96,

	dynamicItemsLoadURL: '/ajax/handler.php',
	moreEventsAjaxURL: '/ajax/more_events.php',

	galleryColorboxParams: {
		transition: 'fade',
		height: '80%',
		opacity: 0.7,
		rel: 'photos',
	},

	blankTransparentPixelURL: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=',

};

/** Required set before "getVal" */
module.exports.required = [
	'lang',
	'revision',
	'tplPath',
	'debug',
];
