<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("iblock")) return;

$arIBlockTypes = CIBlockParameters::GetIBlockTypes(array("-"=>""));

$arIBlockList = array();
$res = CIBlock::GetList(
	array("SORT" => "ASC"),
	array(
		"ACTIVE" => "Y",
		"TYPE" => $arCurrentValues["IBLOCK_TYPE"],
	)
);
while ($arRes = $res->Fetch()) {
	$arIBlockList[$arRes["ID"]] = $arRes["NAME"];
}

$arComponentParameters = array(
	"GROUPS" => array(
		"IBLOCK" => array(
			"SORT" => 100,
			"NAME" => GetMessage("G_IBLOCK"),
		),
	),
	"PARAMETERS" => array(

		/** iblock */

		"IBLOCK_TYPE" => array(
			"PARENT" => "IBLOCK",
			"NAME" => GetMessage("F_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockTypes,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "IBLOCK",
			"NAME" => GetMessage("F_IBLOCK_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockList,
			"REFRESH" => "Y",
		),
		"ELEMENT_CODE" => array(
			"PARENT" => "IBLOCK",
			"NAME" => GetMessage("F_ELEMENT_CODE"),
			"TYPE" => "TEXT",
		),

		/** cache */

		"CACHE_TIME" => array("DEFAULT" => 36000000),
		"CACHE_FILTER" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("F_CACHE_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),

	),
);
