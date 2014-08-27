/**
 * Just log exception
 *
 * @author Viacheslav Lotsmanov
 */

module.exports = function (err) {
	if (window.console && window.console.error) window.console.error(err);
};
