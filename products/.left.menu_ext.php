<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$iblockType = 'lists';
$iblockID = 4;

if (!CModule::IncludeModule('iblock')) return;

global $APPLICATION;

$aMenuLinks = array();

require $_SERVER['DOCUMENT_ROOT'].'/inc/get_for_list.php';
require $_SERVER['DOCUMENT_ROOT'].'/inc/get_products_sections.php';

$arIBlock = GetIBlock($iblockID, $iblockType);

$additionalFilter = array('PROPERTY_BRAND' => false);

$forList = $getForList($iblockType, $iblockID, $additionalFilter);
if (!is_array($forList)) return;

$forList = $addLinksToForList($forList, $arIBlock['LIST_PAGE_URL']);
$forListActive = $filterNonEmptyForListSections($forList);

$curFor = false;
$curPage = $_SERVER['REQUEST_URI'];
$curPage = explode('?', $curPage);
$curPage = $curPage[0];
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
		$iblockType, $iblockID,
		array('SORT'=>'ASC'), $curFor,
		$arIBlock['LIST_PAGE_URL'], $additionalFilter);

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
		$fixedPagePrefix . 'all/',
		array(),
		array(),
		''
	);
}
