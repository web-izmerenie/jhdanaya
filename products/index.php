<?
define('COLLECTION_PAGE', 'Y');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Изделия");
?><?$APPLICATION->IncludeComponent("bitrix:news", "news.products", Array(
	"IBLOCK_TYPE" => "lists",	// Тип инфоблока
	"IBLOCK_ID" => "4",	// Инфоблок
	"NEWS_COUNT" => "6",	// Количество новостей на странице
	"USE_SEARCH" => "N",	// Разрешить поиск
	"USE_RSS" => "N",	// Разрешить RSS
	"USE_RATING" => "N",	// Разрешить голосование
	"USE_CATEGORIES" => "N",	// Выводить материалы по теме
	"USE_FILTER" => "N",	// Показывать фильтр
	"SORT_BY1" => "",	// Поле для первой сортировки новостей
	"SORT_ORDER1" => "",	// Направление для первой сортировки новостей
	"SORT_BY2" => "",	// Поле для второй сортировки новостей
	"SORT_ORDER2" => "",	// Направление для второй сортировки новостей
	"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
	"SEF_MODE" => "Y",	// Включить поддержку ЧПУ
	"AJAX_MODE" => "N",	// Включить режим AJAX
	"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
	"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
	"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
	"CACHE_TYPE" => "A",	// Тип кеширования
	"CACHE_TIME" => "7200",	// Время кеширования (сек.)
	"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
	"CACHE_GROUPS" => "Y",	// Учитывать права доступа
	"SET_STATUS_404" => "Y",	// Устанавливать статус 404, если не найдены элемент или раздел
	"SET_TITLE" => "N",	// Устанавливать заголовок страницы
	"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",	// Включать инфоблок в цепочку навигации
	"ADD_SECTIONS_CHAIN" => "Y",	// Включать раздел в цепочку навигации
	"ADD_ELEMENT_CHAIN" => "N",	// Включать название элемента в цепочку навигации
	"USE_PERMISSIONS" => "N",	// Использовать дополнительное ограничение доступа
	"DISPLAY_DATE" => "Y",	// Выводить дату элемента
	"DISPLAY_PICTURE" => "Y",	// Выводить изображение для анонса
	"DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
	"USE_SHARE" => "N",	// Отображать панель соц. закладок
	"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
	"LIST_ACTIVE_DATE_FORMAT" => "",	// Формат показа даты
	"LIST_FIELD_CODE" => array(	// Поля
		0 => "",
		1 => "",
	),
	"LIST_PROPERTY_CODE" => array(	// Свойства
		0 => "",
		1 => "",
	),
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
	"DISPLAY_NAME" => "Y",	// Выводить название элемента
	"META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
	"META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
	"BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
	"DETAIL_ACTIVE_DATE_FORMAT" => "",	// Формат показа даты
	"DETAIL_FIELD_CODE" => array(	// Поля
		0 => "",
		1 => "",
	),
	"DETAIL_PROPERTY_CODE" => array(	// Свойства
		0 => "",
		1 => "",
	),
	"DETAIL_DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
	"DETAIL_DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
	"DETAIL_PAGER_TITLE" => "Страница",	// Название категорий
	"DETAIL_PAGER_TEMPLATE" => "",	// Название шаблона
	"DETAIL_PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
	"PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
	"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
	"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
	"PAGER_TITLE" => "Новости",	// Название категорий
	"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
	"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
	"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
	"SEF_FOLDER" => "/products/",	// Каталог ЧПУ (относительно корня сайта)
	"SEF_URL_TEMPLATES" => array(
		"news" => "",
		"section" => "#SECTION_CODE#/",
		"detail" => "#SECTION_CODE#/#ELEMENT_CODE#/",
	),
	"VARIABLE_ALIASES" => array(
		"news" => "",
		"section" => "",
		"detail" => "",
	)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
