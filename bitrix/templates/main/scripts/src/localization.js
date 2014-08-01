/**
 * Localization values
 *
 * @author Viacheslav Lotsmanov
 * @encoding utf-8
 */

define(['get_val'], function (getVal) {

	var locals = {

		'ru': {

			'ERR': {
				'AJAX': 'Произошла ошибка динамической загрузки данных.',
				'AJAX_PARSE': 'Произошла ошибка обработки динамически загруженных данных.',
			},

		},

		'defaultLocal': getVal('lang')

	};

	return locals;

}); // define()
