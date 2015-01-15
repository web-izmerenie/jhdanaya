<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$APPLICATION->IncludeComponent(
	"custom:products.sections.list",
	"",
	Array(
		"IBLOCK_TYPE" => $arResult['IBLOCK_TYPE'],
		"IBLOCK_ID" => $arResult['IBLOCK_ID'],
		"BRAND" => $arParams['BRAND'],
		"FOR" => $arResult['FOR'],
		"SORT_BY1" => $arParams['SORT_SECTIONS_BY1'],
		"SORT_ORDER1" => $arParams['SORT_SECTIONS_ORDER1'],
		"SORT_BY2" => $arParams['SORT_SECTIONS_BY2'],
		"SORT_ORDER2" => $arParams['SORT_SECTIONS_ORDER2'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"CACHE_FILTER" => $arParams['CACHE_FILTER']
	)
);?>
