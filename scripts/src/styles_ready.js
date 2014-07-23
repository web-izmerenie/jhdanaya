/**
 * Styles ready module
 *
 * @author Viacheslav Lotsmanov
 */

define(['get_val', 'jquery'], function (getVal, $) {
	return function (cb) {
		$(function domReady() {
			var $f = $('footer');
			function loop() {
				if ($f.height() !== getVal('footerHeight')) {
					setTimeout(loop, getVal('waiterTimeout'));
					return;
				}
				setTimeout(cb, 1);
			}
			loop();
		}); // domReady()
	}; // return f()
}); // define()
