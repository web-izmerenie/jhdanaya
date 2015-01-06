<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$iblock_type = 'lists';
$iblock_id = '4';

if (!CModule::IncludeModule('iblock')) return;

global $APPLICATION;

$aMenuLinks = array();

require $_SERVER['DOCUMENT_ROOT'].'/inc/get_for_list.php';
require $_SERVER['DOCUMENT_ROOT'].'/inc/get_products_sections.php';

// get iblock
$arIBlock = GetIBlock($iblock_id, $iblock_type);

$forList = $getForList($iblock_type, $iblock_id);
if (!is_array($forList)) return;

$forList = $addLinksToForList($forList, $arIBlock['LIST_PAGE_URL']);
$forListActive = $filterNonEmptyForListSections($forList);

$curFor = false;
$curPage = $APPLICATION->GetCurPage();
$fixedPagePrefix = null;

foreach ($forList as $arItem) {
	if (stripos($curPage, $arIBlock['LIST_PAGE_URL'].$arItem['CODE'].'/') === 0) {
		$curFor = $arItem;
		$fixedPagePrefix = $arIBlock['LIST_PAGE_URL'].$arItem['CODE'].'/';
	}
}

if ($fixedPagePrefix === null) {
	$fixedPagePrefix = $arIBlock['LIST_PAGE_URL'];
}

if (strlen($fixedPagePrefix) < strlen($curPage)) {
	$sectionsList = $getProductsSections(
		$iblock_type, $iblock_id, array('SORT'=>'ASC'), $curFor,
		$arIBlock['LIST_PAGE_URL'], array('PROPERTY_BRAND' => false));

	foreach ($sectionsList as $arSection) {
		$aMenuLinks[] = array(
			$arSection["NAME"],
			$arSection["SECTION_PAGE_URL"],
			array(),
			array(),
			""
		);
	}

	$aMenuLinks[] = array(
		GetMessage('ALL'),
		$fixedPagePrefix . '?show=all',
		array(),
		array(),
		''
	);
}



















return;

$dir = $APPLICATION->GetCurDir();
$page = $APPLICATION->GetCurPage();

$arDir = explode("/", $dir);
foreach($arDir as $k => $v){
	if($v === "") unset($arDir[$k]);
}

if(
	(count($arDir) > 1 || $_GET["show"] === "all") &&
	(
		$page != "/products/women/" &&
		$page != "/products/men/" &&
		$page != "/products/cildren/"
	)
){
	CModule::IncludeModule("iblock");
	$aMenuLinks = array();
	$section = CIBlockSection::GetList(
		array(
			"SORT" => "asc"
		),
		array(
			"ACTIVE" => "Y",
			"IBLOCK_CODE" => "catalog",
			"PROPERTY" => array(
				"BRAND" => false
			)
		),
		true
	);
	if($section->SelectedRowsCount()){
		while($arSection = $section->GetNExt()){
			if($arSection["ELEMENT_CNT"]){
				if(stripos($APPLICATION->GetCurDir(), "/women/"))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/women", $arSection["SECTION_PAGE_URL"]);
				if(stripos("/men/", $APPLICATION->GetCurDir()))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/men", $arSection["SECTION_PAGE_URL"]);
				if(stripos("/children/", $APPLICATION->GetCurDir()))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/children", $arSection["SECTION_PAGE_URL"]);

				$aMenuLinks[] = Array(
					$arSection["NAME"],
					$arSection["SECTION_PAGE_URL"],
					array(),
					array(),
					""
				);
			}
		}
		$aMenuLinks[] = Array(
			GetMessage("ALL"),
			$arDir[0] . "?show=all",
			array(),
			array(),
			""
		);
	}
}
