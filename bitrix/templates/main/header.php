<?
	IncludeTemplateLangFile(__FILE__);

	$revision = 13;
	$debug = false;

	if($USER->IsAdmin()) $debug = true;
	if($debug) $revision = 'dev' . mktime();

	$html_classes = array();
	$main_classes = array();

	// <html> classes
	if(defined("HTML_MAIN_PAGE"))
		$html_classes[] = "main_page";
	if(defined("COLLECTION_PAGE")){
		$html_classes[] = "collection_page";
		if(!defined('COLLECTION_BRAND_DETAIL_PAGE'))
			$html_classes[] = "content_bg";
	}
	if(defined('EVENT_DETAIL_PAGE'))
		$html_classes[] = "event_detail_page";
	if(defined('ERROR_404'))
		$html_classes[] = "error_404_page";

	if(defined('VACANCIES_PAGE'))
		$html_classes[] = "vacancies_page";

	// <main> classes
	if(defined("MAIN_ABOUT"))
		$main_classes[] = "about";
	if(defined("MAIN_SHOPS"))
		$main_classes[] = "shops";
	if(defined('COLLECTION_BRAND_DETAIL_PAGE')){
		$main_classes[] = "stretch";
		$main_classes[] = "no_limits";
	}

	$html_classes = implode(" ", $html_classes);
	$main_classes = implode(" ", $main_classes);

	$tplPath = '/bitrix/templates/main';

	if($_REQUEST["BRAND"]){
		if(stripos($_REQUEST["BRAND"], "?show=all")){
			$_REQUEST["BRAND"] = str_replace("?show=all", "", $_REQUEST["BRAND"]);
		}
	}

?><!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID
	?>" lang="<?=LANGUAGE_ID
	?>" data-revision="<?=$revision
	?>" data-template-path="<?=$tplPath
	?>" data-debug="<?=($debug) ? 'true' : 'false'
	?>" class="<?=$html_classes?>">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=980" />
	<title><?$APPLICATION->ShowTitle()?></title>

	<!--[if lt IE 10]>
		<meta http-equiv="refresh" content="0; url=/ie_old/<?=LANGUAGE_ID?>.html" />
		<style>* { display: none !important; }</style>
		<script>throw new Error('IE less than 10');</script>
	<![endif]-->

	<link href="/favicon.ico?v=<?=$revision?>" rel="shortcut icon" type="image/x-icon" />

	<?/* styles */?>
	<?if($debug && 1===2){?>
		<?/* client-side compiling */?>
		<?/* now this feature disabled (by ` && 1===2`), 'cause compiling on client-side has annoying cache issues */?>
		<script>var less = { env: 'development' };</script>
		<link rel="stylesheet/less" type="text/css" href="<?=$tplPath?>/styles/src/main.less?v=<?=$revision?>" />
		<script src="<?=$tplPath?>/scripts/alone/less.js?v=<?=$revision?>"></script>
	<?}else{?>
		<link rel="stylesheet" href="<?=$tplPath?>/styles/build/build.css?v=<?=$revision?>" />
	<?}?>

	<?if($USER->IsAuthorized()){?>
		<?$APPLICATION->ShowHead()?>
	<?}?>

	<script src="<?=$tplPath?>/scripts/build/build.js?v=<?=$revision?>"></script>
</head>

<body>
	<?$APPLICATION->ShowPanel()?>
	<?if(defined('VACANCIES_PAGE')){?><div class="vacancies_bg"></div><?}?>
	<header>
		<?if($APPLICATION->GetCurPage(0) != SITE_DIR){?>
		<a class="logo" href="<?=SITE_DIR?>" title="<?=GetMessage("GOTO_MAINPAGE")?>">
		<?}?>
			<img class="logo" alt="<?=GetMessage("MAIN_SLOGAN")?>" src="<?=$tplPath?>/images/header_logo.png" />
		<?if($APPLICATION->GetCurPage(0) != SITE_DIR){?>
		</a>
		<?}?>
		<?if(!defined('ERROR_404')){?>
			<nav class="menu">
				<?$APPLICATION->IncludeComponent("bitrix:menu", "menu.main", Array(
					"ROOT_MENU_TYPE" => "top",	// Тип меню для первого уровня
					"MENU_CACHE_TYPE" => "A",	// Тип кеширования
					"MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
					"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
					"MENU_CACHE_GET_VARS" => array(	// Значимые переменные запроса
						0 => "",
					),
					"MAX_LEVEL" => "1",	// Уровень вложенности меню
					"CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
					"USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
					"DELAY" => "N",	// Откладывать выполнение шаблона меню
					"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
					),
					false
				);?>
			</nav>
		<?}?>
	</header>
	<?if(!defined("HTML_MAIN_PAGE")){?>
		<?CModule::IncludeModule("iblock");?>
		<?if(defined('COLLECTION_BRAND_PAGE')){?>
			<?if(CSite::InDir("/brand/")){
				$tplPathBrand = $APPLICATION->GetCurPage();
				$tplPathBrand = explode("/", $tplPathBrand);
				foreach($tplPathBrand as $k => $v){
					if($v === "") unset($tplPathBrand[$k]);
				}

				$rs_brand = CIBlockElement::GetList(
					array(),
					array(
						"IBLOCK_TYPE" => "lists",
						"IBLOCK_CODE" => "brand",
						"CODE" => $tplPathBrand[2]
					),
					false,
					false,
					array()
				);

				$ar_brand = $rs_brand->GetNextElement();
				$ar_brand_f = $ar_brand->GetFields();
				$ar_brand_p = $ar_brand->GetProperties();
				$currentBrendID = $ar_brand_f["ID"];

				$aboutBrandPage = str_replace("brand", "brand/about", $APPLICATION->GetCurPage());
				$aboutBrandPage = explode("/", $aboutBrandPage);

				foreach($aboutBrandPage as $k => $v){
					if($v === "") unset($aboutBrandPage[$k]);
				}

				$aboutBrandPage = "/" . $aboutBrandPage[1] . "/" . $aboutBrandPage[2] . "/" . $aboutBrandPage[3] . "/";

				$ar_small_piture = CFile::GetFileArray($ar_brand_p["SMALL_PICTURE"]["VALUE"]);
				if($ar_small_piture["width"] > 57){
					$small_picture = CFile::ResizeImageGet($ar_brand_p["SMALL_PICTURE"]["VALUE"], array("width" => "57", "height" => "57"), BX_RESIZE_IMAGE_PROPORTIONAL);
				}else{
					$small_picture["src"] = CFile::GetPath($ar_brand_p["SMALL_PICTURE"]["VALUE"]);
				}
				$title_picture["src"] = CFile::GetPath($ar_brand_p["TITLE_PICTURE"]["VALUE"]);
			}?>
			<div class="collection_brand_page_headline">
				<h1>
					<?if(!empty($title_picture["src"])){?>
						<img alt="<?=$ar_brand_f["NAME"]?>" src="<?=$title_picture["src"]?>" />
					<?}else{?>
						<?=$ar_brand_f["NAME"]?>
					<?}?>
				</h1>
				<a href="<?=$aboutBrandPage?>" class="about_brand">
					<img alt="" src="<?=$small_picture["src"]?>" />
					<span><?=GetMessage("ABOUT_BRAND")?></span>
				</a>
			</div>
		<?}elseif(defined('COLLECTION_BRAND_DETAIL_PAGE')){?>
			<?if(CSite::InDir("/brand/")){
				$collectionPageBrand = str_replace("/about", "", $APPLICATION->GetCurPage());
				$collectionPageBrand = explode("/", $collectionPageBrand);
				foreach($collectionPageBrand as $k => $v){
					if($v === "") unset($collectionPageBrand[$k]);
				}

				$rs_brand = CIBlockElement::GetList(
					array(),
					array(
						"IBLOCK_TYPE" => "lists",
						"IBLOCK_CODE" => "brand",
						"CODE" => $collectionPageBrand[2]
					),
					false,
					false,
					array()
				);

				$ar_brand = $rs_brand->GetNextElement();
				$ar_brand_f = $ar_brand->GetFields();
				$ar_brand_p = $ar_brand->GetProperties();
				$currentBrendID = $ar_brand_f["ID"];

				$ar_small_piture = CFile::GetFileArray($ar_brand_p["SMALL_PICTURE"]["VALUE"]);
				if($ar_small_piture["width"] > 57){
					$small_picture = CFile::ResizeImageGet($ar_brand_p["SMALL_PICTURE"]["VALUE"], array("width" => "57", "height" => "57"), BX_RESIZE_IMAGE_PROPORTIONAL);
				}else{
					$small_picture["src"] = CFile::GetPath($ar_brand_p["SMALL_PICTURE"]["VALUE"]);
				}
				$title_picture["src"] = CFile::GetPath($ar_brand_p["TITLE_PICTURE"]["VALUE"]);

				$collectionPageBrand = "/".implode("/", $collectionPageBrand)."/?show=all";
			}?>
			<div class="collection_brand_page_headline">
				<h1><img alt="<?=$ar_brand_f["NAME"]?>" src="<?=$title_picture["src"]?>" /></h1>
				<a href="<?=$collectionPageBrand?>" class="about_brand">
					<img alt="" src="<?=$small_picture["src"]?>" />
					<span><?=GetMessage("COLLECTION_BRAND")?></span>
				</a>
			</div>
		<?}else{?>
			<h1><?$APPLICATION->ShowTitle()?></h1>
		<?}?>
		<?$APPLICATION->IncludeComponent("bitrix:menu", "menu.sub", Array(
			"ROOT_MENU_TYPE" => "left",	// Тип меню для первого уровня
			"MENU_CACHE_TYPE" => "A",	// Тип кеширования
			"MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
			"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
			"MENU_CACHE_GET_VARS" => array(	// Значимые переменные запроса
				0 => "",
			),
			"MAX_LEVEL" => "1",	// Уровень вложенности меню
			"CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
			"USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
			"DELAY" => "N",	// Откладывать выполнение шаблона меню
			"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
			"BRAND_NAME" => $tplPathBrand[2]
			),
			false
		);?>
	<?}//if(!defined("HTML_MAIN_PAGE"))?>
	<?if(!defined('ERROR_404')){?><main class="<?=$main_classes?>"><?}?>
		<?if(defined("HTML_MAIN_PAGE")){?>
			<section class="top_card">
				<div class="logo">
					<img class="logo" alt="<?=GetMessage("MAIN_SLOGAN")?>" src="<?=$tplPath?>/images/main_page/logo.png" />
					<div class="istablish"><?=GetMessage("BASED_AT")?></div>
				</div>
				<div class="slider"><?$APPLICATION->IncludeComponent("bitrix:news.list", "news.list.slider", array(
	"IBLOCK_TYPE" => "service",
	"IBLOCK_ID" => "2",
	"NEWS_COUNT" => "1000",
	"SORT_BY1" => "SORT",
	"SORT_ORDER1" => "ASC",
	"SORT_BY2" => "",
	"SORT_ORDER2" => "",
	"FILTER_NAME" => "",
	"FIELD_CODE" => array(
		0 => "ID",
		1 => "CODE",
		2 => "XML_ID",
		3 => "NAME",
		4 => "TAGS",
		5 => "SORT",
		6 => "PREVIEW_TEXT",
		7 => "PREVIEW_PICTURE",
		8 => "DETAIL_TEXT",
		9 => "DETAIL_PICTURE",
		10 => "DATE_ACTIVE_FROM",
		11 => "ACTIVE_FROM",
		12 => "DATE_ACTIVE_TO",
		13 => "ACTIVE_TO",
		14 => "SHOW_COUNTER",
		15 => "SHOW_COUNTER_START",
		16 => "IBLOCK_TYPE_ID",
		17 => "IBLOCK_ID",
		18 => "IBLOCK_CODE",
		19 => "IBLOCK_NAME",
		20 => "IBLOCK_EXTERNAL_ID",
		21 => "DATE_CREATE",
		22 => "CREATED_BY",
		23 => "CREATED_USER_NAME",
		24 => "TIMESTAMP_X",
		25 => "MODIFIED_BY",
		26 => "USER_NAME",
		27 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"CHECK_DATES" => "Y",
	"DETAIL_URL" => "",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "N",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "7200",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"PREVIEW_TRUNCATE_LEN" => "",
	"ACTIVE_DATE_FORMAT" => "",
	"SET_STATUS_404" => "N",
	"SET_TITLE" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",
	"PARENT_SECTION" => "",
	"PARENT_SECTION_CODE" => "",
	"INCLUDE_SUBSECTIONS" => "Y",
	"PAGER_TEMPLATE" => "",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "N",
	"PAGER_TITLE" => "",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "N",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>
				</div>
				<a class="next_card"><span></span></a>
			</section><!--.top_card-->
			<section class="brands" id="brands"><?$APPLICATION->IncludeComponent("bitrix:news", "news.brand", Array(
	"IBLOCK_TYPE" => "lists",	// Тип инфоблока
	"IBLOCK_ID" => "3",	// Инфоблок
	"NEWS_COUNT" => "1000",	// Количество новостей на странице
	"USE_SEARCH" => "N",	// Разрешить поиск
	"USE_RSS" => "N",	// Разрешить RSS
	"USE_RATING" => "N",	// Разрешить голосование
	"USE_CATEGORIES" => "N",	// Выводить материалы по теме
	"USE_FILTER" => "N",	// Показывать фильтр
	"SORT_BY1" => "SORT",	// Поле для первой сортировки новостей
	"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
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
	"SET_STATUS_404" => "N",	// Устанавливать статус 404, если не найдены элемент или раздел
	"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
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
	"SEF_FOLDER" => "/brand/",	// Каталог ЧПУ (относительно корня сайта)
	"SEF_URL_TEMPLATES" => array(
		"news" => "",
		"section" => "",
		"detail" => "#ELEMENT_CODE#/",
	),
	"VARIABLE_ALIASES" => array(
		"news" => "",
		"section" => "",
		"detail" => "",
	)
	),
	false
);?>
			</section><!--.brands-->
		<?}//if(!defined('ERROR_404'))?>
