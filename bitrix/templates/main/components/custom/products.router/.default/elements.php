<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$APPLICATION->IncludeComponent(
	"custom:products.elements.list",
	"",
	Array(
		"IBLOCK_TYPE" => $arResult['IBLOCK_TYPE'],
		"IBLOCK_ID" => $arResult['IBLOCK_ID'],
		"SECTION_CODE" => $arResult['SECTION_CODE'],
		"FOR" => $arResult['FOR'],
		"BRAND" => $arParams['BRAND'],
		"ELEMENTS_ON_PAGE" => "12",
		"SORT_BY1" => $arParams['SORT_ELEMENTS_BY1'],
		"SORT_ORDER1" => $arParams['SORT_ELEMENTS_ORDER1'],
		"SORT_BY2" => $arParams['SORT_ELEMENTS_BY2'],
		"SORT_ORDER2" => $arParams['SORT_ELEMENTS_ORDER2'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"CACHE_FILTER" => $arParams['CACHE_FILTER'],
		"PAGER_TEMPLATE" => "products",
		"DISPLAY_TOP_PAGER" => "Y",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N"
	)
);?>
