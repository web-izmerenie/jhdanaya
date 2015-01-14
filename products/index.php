<?
define('COLLECTION_PAGE', 'Y');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Изделия");
?><?$APPLICATION->IncludeComponent(
	"custom:products.router",
	"",
	Array(
		"IBLOCK_TYPE" => "lists",
		"IBLOCK_ID" => "4",
		"SORT_SECTIONS_BY1" => "SORT",
		"SORT_SECTIONS_ORDER1" => "ASC",
		"SORT_SECTIONS_BY2" => "",
		"SORT_SECTIONS_ORDER2" => "",
		"SORT_ELEMENTS_BY1" => "SORT",
		"SORT_ELEMENTS_ORDER1" => "ASC",
		"SORT_ELEMENTS_BY2" => "",
		"SORT_ELEMENTS_ORDER2" => "",
		"ROUTE_PATH" => $_SERVER['REQUEST_URI'],
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
