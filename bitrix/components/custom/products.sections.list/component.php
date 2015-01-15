<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// init requirements {{{1

$requiredModules = array('iblock', 'highloadblock');

foreach ($requiredModules as $requiredModule) {
	if (!CModule::IncludeModule($requiredModule)) {
		ShowError(GetMessage(
			"F_NO_MODULE", array('#MODULE_NAME#' => $requiredModule)));
		return 0;
	}
}

// init requirements }}}1

require $_SERVER['DOCUMENT_ROOT'].'/inc/get_for_list.php';
require $_SERVER['DOCUMENT_ROOT'].'/inc/get_products_sections.php';

if ($this->StartResultCache(false)) {

	$arIBlock = GetIBlock($arParams["IBLOCK_ID"], $arParams["IBLOCK_TYPE"]);
	$arResult['IBLOCK'] = $arIBlock;

	$additionalFilter = array();
	if (!empty($arParams['BRAND'])) {
		$additionalFilter['PROPERTY_BRAND'] = (
			$arParams['BRAND'] !== '-' ? $arParams['BRAND'] : false);
	}

	// get list values by "FOR" property {{{1

	$forList = $getForList(
		$arParams['IBLOCK_TYPE'],
		$arParams['IBLOCK_ID'],
		$additionalFilter);
	if (!is_array($forList)) {
		ShowError(GetMessage($forList));
		CHTTP::SetStatus('500 Internal Server Error');
		$this->AbortResultCache();
		return;
	}

	$arResult['FOR_LIST'] = $addLinksToForList(
		$forList, $arResult['IBLOCK']['LIST_PAGE_URL']);

	$arResult['FOR_LIST_ACTIVE'] =
		$filterNonEmptyForListSections($arResult['FOR_LIST']);

	// if we on page that filtered by "FOR" property
	$arResult['FOR_PAGE'] = false;
	if (!empty($arParams['FOR'])) {
		// find current page "FOR" element
		foreach ($arResult['FOR_LIST'] as $arItem) {
			if ($arItem['CODE'] != $arParams['FOR']) continue;
			$arResult['FOR_PAGE'] = $arItem;
		}
	}

	// get list values by "FOR" property }}}1

	$arSort = array(
		$arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
		$arParams["SORT_BY2"] => $arParams["SORT_ORDER2"],
	);

	$arResult['ITEMS'] = array();

	$sectionsList = $getProductsSections(
		$arParams["IBLOCK_TYPE"], $arParams["IBLOCK_ID"], $arSort,
		$arResult['FOR_PAGE'], $arResult['IBLOCK']['LIST_PAGE_URL'],
		$additionalFilter);

	foreach ($sectionsList as $arSection) {
		$arSection['PICTURE'] = CFile::GetFileArray($arSection['PICTURE']);
		$arResult['ITEMS'][] = $arSection;
	}

	$this->SetResultCacheKeys();
	$this->IncludeComponentTemplate();

}
