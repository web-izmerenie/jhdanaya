<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$APPLICATION->IncludeComponent(
	"custom:products.detail",
	"",
	Array(
		"IBLOCK_TYPE" => $arResult['IBLOCK_TYPE'],
		"IBLOCK_ID" => $arResult['IBLOCK_ID'],
		"SECTION_CODE" => $arResult['SECTION_CODE'],
		"FOR" => $arResult['FOR'],
		"BRAND" => $arParams['BRAND'],
		"ELEMENT_CODE" => $arResult['ELEMENT_CODE'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"CACHE_FILTER" => $arParams['CACHE_FILTER'],
	)
);?>
