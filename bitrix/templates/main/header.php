<?
    IncludeTemplateLangFile(__FILE__);

    $revision = 2;
    if($USER->IsAdmin()) $revision = 'dev' . mktime();

    $html_classes = array();
    $main_classes = array();

    if(defined("MAIN_ABOUT"))
        $main_classes[] = "about";
    if(defined("MAIN_SHOPS"))
        $main_classes[] = "shops";

    if(defined("HTML_MAIN_PAGE"))
        $html_classes[] = "main_page";

    $html_classes = implode(" ", $html_classes);
    $main_classes = implode(" ", $main_classes);

	$tplPath = '/bitrix/templates/main';

?><!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" class="<?=$html_classes?>">
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
	<link rel="stylesheet/less" type="text/css" href="<?=$tplPath?>/styles/src/main.less?v=<?=$revision?>" />
    <?/*<link rel="stylesheet" href="<?=$tplPath?>/styles/build/build.css?v=<?=$revision?>" />*/?>
	<script src="<?=$tplPath?>/scripts/src/libs/require.js?v=<?=$revision?>"></script>
    <?$APPLICATION->ShowCSS()?>
    <?$APPLICATION->ShowHeadStrings()?>
	<script>
		//<![CDATA[
			require.config({
				baseUrl: '<?=$tplPath?>/scripts/src/',
				urlArgs: 'v=<?=$revision?>',
			});
			require(['basics/get_val'], function (getVal) {
				getVal.set('clientSide', true);
				getVal.set('lang', '<?=LANGUAGE_ID?>');
				getVal.set('revision', '<?=$revision?>');
				getVal.set('tplPath', '<?=$tplPath?>');
				require(['main']);
			});
		//]]>
	</script>
</head>

<body><?$APPLICATION->ShowPanel()?>
	<header><?
        if($APPLICATION->GetCurPage(0) != SITE_DIR){?>
            <a class="logo" href="<?=SITE_DIR?>" title="<?=GetMessage("GOTO_MAINPAGE")?>"><?
        }?>
			<img class="logo" alt="<?=GetMessage("MAIN_SLOGAN")?>" src="<?=$tplPath?>/images/header_logo.png" /><?
        if($APPLICATION->GetCurPage(0) != SITE_DIR){?>
            </a><?
        }?>
		<nav class="menu"><?$APPLICATION->IncludeComponent("bitrix:menu", "menu.main", Array(
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
	</header><?
    if(!defined("HTML_MAIN_PAGE")){?>
        <h1><?$APPLICATION->ShowTitle()?></h1>
        <nav class="nav_block"><?$APPLICATION->IncludeComponent("bitrix:menu", "menu.sub", Array(
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
	),
	false
);?>
        </nav><?
    }?>
	<main class="<?=$main_classes?>"><?
        if(defined("HTML_MAIN_PAGE")){?>
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
            </section>
            <section class="brands">
                <ul class="brands_list">
                    <li><a style="cursor:default">Pasquale Bruni</a></li>
                    <li><a style="cursor:default">Giovanni Ferraris</a></li>
                    <li><a style="cursor:default">Gucci</a></li>
                    <li><a style="cursor:default">ID Broggian</a></li>
                    <li><a style="cursor:default">Nanis</a></li>
                </ul>
            </section><?
        }?>
