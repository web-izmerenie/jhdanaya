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

require dirname(__FILE__).'/get_for_list.php';

if ($this->StartResultCache(false)) {

	// prepare params {{{1

	$arSort = array(
		$arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
		$arParams["SORT_BY2"] => $arParams["SORT_ORDER2"],
	);

	$arFilter = array(
		"ACTIVE" => "Y",
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	);

	// prepare params }}}1

	// get iblock {{{1

	$arIBlock = GetIBlock($arParams["IBLOCK_ID"], $arParams["IBLOCK_TYPE"]);
	$arResult['IBLOCK'] = $arIBlock;

	// get iblock }}}1

	// get list values by "FOR" property {{{1

	$forList = $getForList($arParams['IBLOCK_TYPE'], $arParams['IBLOCK_ID']);
	if (!is_array($forList)) {
		ShowError(GetMessage($forList));
		CHTTP::SetStatus('500 Internal Server Error');
		$this->AbortResultCache();
		return;
	}

	$arResult['FOR_LIST'] = $forList;

	// add links to items
	$newForList = array();
	foreach ($arResult['FOR_LIST'] as $arItem) {
		$arItem['LINK'] = $arResult['IBLOCK']['LIST_PAGE_URL'].$arItem['CODE'].'/';
		$newForList[] = $arItem;
	}
	$arResult['FOR_LIST'] = $newForList;

	// only items that contains active elements in active sections
	$arResult['FOR_LIST_ACTIVE'] = array();
	foreach ($arResult['FOR_LIST'] as $arItem) {
		if ($arItem['COUNT'] <= 0) continue;
		$arResult['FOR_LIST_ACTIVE'][] = $arItem;
	}

	// if we on page that filtered by "FOR" property
	$arResult['FOR_PAGE'] = false;
	if (
		is_array($arParams['ADDITIONAL_FILTER']) &&
		!empty($arParams['ADDITIONAL_FILTER']['PROPERTY_FOR'])
	) {
		$arResult['FOR_PAGE'] = $arParams['ADDITIONAL_FILTER']['PROPERTY_FOR'];
		$arResult['FOR_PAGE_LIST_ITEM'] = null;

		// find current page "FOR" element
		foreach ($arResult['FOR_LIST'] as $arItem) {
			if ($arItem['CODE'] != $arResult['FOR_PAGE']) continue;
			$arResult['FOR_PAGE_LIST_ITEM'] = $arItem;
		}
	}

	// get list values by "FOR" property }}}1

	// get active sections list
	// will be filtered more latear by active elements
	$res = CIBlockSection::GetList(
		$arSort,
		$arFilter,
		false,
		array('UF_*')
	);

	$arResult['ITEMS'] = array();

	// prepare elements filter template
	$elFilterTmpl = $arFilter;
	if (is_array($arParams['ADDITIONAL_FILTER'])) {
		$elFilterTmpl = array_merge($elFilterTmpl, $arParams['ADDITIONAL_FILTER']);
	}

	while ($arSection = $res->GetNext()) {

		$arSection['PICTURE'] = CFile::GetFileArray($arSection['PICTURE']);

		// remove section from list if it hasn't any active elements
		$elFilter = array_merge(
			array('SECTION_ID' => $arSection['ID']), $elFilterTmpl);
		$elRes = CIBlockElement::GetList(array(), $elFilter);
		if ($elRes->SelectedRowsCount() <= 0) continue;

		// if we on page filtered by "FOR" then fix links for this page
		if ($arResult['FOR_PAGE_LIST_ITEM']) {
			$arSection['SECTION_PAGE_URL'] = str_replace(
				$arResult['IBLOCK']['LIST_PAGE_URL'],
				$arResult['IBLOCK']['LIST_PAGE_URL'].$arResult['FOR_PAGE'].'/',
				$arSection['SECTION_PAGE_URL']);
		}

		$arResult['ITEMS'][] = $arSection;
	}

	$this->SetResultCacheKeys();
	$this->IncludeComponentTemplate();

}
