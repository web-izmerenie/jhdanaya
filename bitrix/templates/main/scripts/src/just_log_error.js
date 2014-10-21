/**
 * Just log exception
 *
 * @author Viacheslav Lotsmanov
 * @license GNU/AGPLv3
 * @see {@link https://github.com/web-izmerenie/jhdanaya/blob/master/LICENSE-AGPLv3|License}
 */

module.exports = function (err) {
	if (window.console && window.console.error) window.console.error(err);
};
