/**
 * "loadImg" wrapper for "load_img" alias
 * (see "shim" package.json)
 *
 * @author Viacheslav Lotsmanov
 * @license GNU/AGPLv3
 * @see {@link https://github.com/web-izmerenie/jhdanaya/blob/master/LICENSE-AGPLv3|License}
 */

var basics = require('../basics');

module.exports = function () {
	// delegate
	return basics.loadImg.apply(null, arguments);
};
