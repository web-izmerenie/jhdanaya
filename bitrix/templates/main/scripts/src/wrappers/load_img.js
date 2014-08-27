/**
 * "loadImg" wrapper for "load_img" alias
 * (see "shim" package.json)
 *
 * @author Viacheslav Lotsmanov
 */

var basics = require('../basics');

module.exports = function () {
	// delegate
	return basics.loadImg.apply(null, arguments);
};
