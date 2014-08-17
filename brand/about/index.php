<?
define('COLLECTION_PAGE', 'Y');
define('COLLECTION_BRAND_DETAIL_PAGE', 'Y');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О бренде");
if($APPLICATION->GetCurPage() == "/brand/about/")
    LocalRedirect("/brand/about/pasquale-bruni/");

?><?$APPLICATION->IncludeComponent("bitrix:news.detail", "new.detail.brand", Array(
	"IBLOCK_TYPE" => "lists",	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => "3",	// Код информационного блока
		"ELEMENT_ID" => "",	// ID новости
		"ELEMENT_CODE" => $_REQUEST["BRAND"],	// Код новости
		"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
		"FIELD_CODE" => array(	// Поля
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(	// Свойства
			0 => "SMALL_PICTURE",
			1 => "TITLE_PICTURE",
			2 => "YEAR",
			3 => "PLACE",
			4 => "Q_PICTURE",
			5 => "Q_TEXT",
			6 => "Q_AUTHOR",
			7 => "BL1_PICTURE",
			8 => "BL1_TEXT",
			9 => "BL2_CODE",
			10 => "BL2_TEXT",
			11 => "PROMO_SLOGAN",
			12 => "PROMO_GALLERY"
		),
		"IBLOCK_URL" => "",	// URL страницы просмотра списка элементов (по умолчанию - из настроек инфоблока)
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "7200",	// Время кеширования (сек.)
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
		"META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
		"BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
		"SET_STATUS_404" => "N",	// Устанавливать статус 404, если не найдены элемент или раздел
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",	// Включать инфоблок в цепочку навигации
		"ADD_SECTIONS_CHAIN" => "Y",	// Включать раздел в цепочку навигации
		"ADD_ELEMENT_CHAIN" => "N",	// Включать название элемента в цепочку навигации
		"ACTIVE_DATE_FORMAT" => "",	// Формат показа даты
		"USE_PERMISSIONS" => "N",	// Использовать дополнительное ограничение доступа
		"DISPLAY_DATE" => "Y",	// Выводить дату элемента
		"DISPLAY_NAME" => "Y",	// Выводить название элемента
		"DISPLAY_PICTURE" => "Y",	// Выводить детальное изображение
		"DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
		"USE_SHARE" => "N",	// Отображать панель соц. закладок
		"PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
		"PAGER_TITLE" => "Страница",	// Название категорий
		"PAGER_SHOW_ALL" => "Y",	// Показывать ссылку "Все"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>