<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

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

$arSorts = array(
		"ASC" => GetMessage("SORT_ASC"),
		"DESC" => GetMessage("SORT_DESC")
	);
$arSortFields = array(
		"ID" => GetMessage("SORT_F_ID"),
		"NAME" => GetMessage("SORT_F_NAME"),
		"SORT" => GetMessage("SORT_F_SORT"),
	);

$arComponentParameters = array(
	"GROUPS" => array(
		"IBLOCK" => array(
			"SORT" => 100,
			"NAME" => GetMessage("G_IBLOCK"),
		),
		"SORT" => array(
			"SORT" => 200,
			"NAME" => GetMessage("G_SORT"),
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

		"BRAND" => array(
			"PARENT" => "IBLOCK",
			"NAME" => GetMessage("F_BRAND"),
			"TYPE" => "TEXT",
		),
		"FOR" => array(
			"PARENT" => "IBLOCK",
			"NAME" => GetMessage("F_FOR"),
			"TYPE" => "TEXT",
		),

		/** sort */

		"SORT_BY1" => array(
			"PARENT" => "SORT",
			"NAME" => GetMessage("F_SORT_BY1"),
			"TYPE" => "LIST",
			"DEFAULT" => "SORT",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_ORDER1" => array(
			"PARENT" => "SORT",
			"NAME" => GetMessage("F_SORT_ORDER1"),
			"TYPE" => "LIST",
			"DEFAULT" => "ASC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_BY2" => array(
			"PARENT" => "SORT",
			"NAME" => GetMessage("F_SORT_BY2"),
			"TYPE" => "LIST",
			"DEFAULT" => "",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_ORDER2" => array(
			"PARENT" => "SORT",
			"NAME" => GetMessage("F_SORT_ORDER2"),
			"TYPE" => "LIST",
			"DEFAULT" => "",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
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
