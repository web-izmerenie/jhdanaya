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

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

require $_SERVER['DOCUMENT_ROOT'].'/inc/get_for_list.php';
require $_SERVER['DOCUMENT_ROOT'].'/inc/get_products_sections.php';

// init pagination params {{{1

$arParams["DISPLAY_TOP_PAGER"] = $arParams["DISPLAY_TOP_PAGER"]=="Y";
$arParams["DISPLAY_BOTTOM_PAGER"] = $arParams["DISPLAY_BOTTOM_PAGER"]!="N";
$arParams["PAGER_TITLE"] = trim($arParams["PAGER_TITLE"]);
$arParams["PAGER_SHOW_ALWAYS"] = $arParams["PAGER_SHOW_ALWAYS"]=="Y";
$arParams["PAGER_TEMPLATE"] = trim($arParams["PAGER_TEMPLATE"]);
$arParams["PAGER_DESC_NUMBERING"] = $arParams["PAGER_DESC_NUMBERING"]=="Y";
$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] = intval($arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]);
$arParams["PAGER_SHOW_ALL"] = $arParams["PAGER_SHOW_ALL"]=="Y";

if($arParams["DISPLAY_TOP_PAGER"] || $arParams["DISPLAY_BOTTOM_PAGER"]) {
	$arNavParams = array(
		"nPageSize" => $arParams["ELEMENTS_ON_PAGE"],
		"bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
		"bShowAll" => $arParams["PAGER_SHOW_ALL"],
	);
	$arNavigation = CDBResult::GetNavParams($arNavParams);
	if($arNavigation["PAGEN"]==0 && $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]>0)
		$arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];
} else {
	$arNavParams = array(
		"nTopCount" => $arParams["ELEMENTS_ON_PAGE"],
		"bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
	);
	$arNavigation = false;
}

// init pagination params }}}1

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

	if (is_array($arResult['FOR_PAGE'])) {
		$additionalFilter['PROPERTY_FOR'] = $arResult['FOR_PAGE']['CODE'];
	}

	// get list values by "FOR" property }}}1

	$arSort = array(
		$arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
		$arParams["SORT_BY2"] => $arParams["SORT_ORDER2"],
	);

	$elFilter = $additionalFilter;
	if (!empty($arParams['SECTION_CODE'])) {
		$elFilter['SECTION_CODE'] = $arParams['SECTION_CODE'];
		$resSection = CIBlockSection::GetList(
			array(),
			array_merge(
				array(
					"ACTIVE" => "Y",
					"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
					"IBLOCK_ID" => $arParams['IBLOCK_ID'],
					"CODE" => $arParams['SECTION_CODE'],
				),
				$additionalFilter
			));
		if ($resSection->SelectedRowsCount() !== 1) {
			define('ERROR_404', 'Y');
			CHTTP::SetStatus("404 Not Found");
			ShowError(GetMessage('PAGE_NOT_FOUND'));
			return;
		}
	}

	$arResult['ITEMS'] = array();

	$res = CIBlockElement::GetList(
		$arSort,
		array_merge(array(
			"ACTIVE" => "Y",
			"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
			"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		), $elFilter),
		false,
		$arNavParams,
		null);

	while ($elem = $res->GetNextElement()) {
		$item = array();

		$fields = $elem->GetFields();
		$props = $elem->GetProperties();

		$item['ID'] = $fields['ID'];
		$item['NAME'] = $fields['NAME'];
		$item['SORT'] = $fields['SORT'];
		$item['PREVIEW_PICTURE'] = CFile::GetFileArray($fields['PREVIEW_PICTURE']);
		$item['DETAIL_PICTURE'] = CFile::GetFileArray($fields['DETAIL_PICTURE']);
		$item['PREVIEW_TEXT'] = $fields['PREVIEW_TEXT'];
		$item['ARTICLE'] = $props['ARTICLE']['VALUE'];
		$item['ART'] = GetMessage("ART.").'&nbsp;'.$props['ARTICLE']['VALUE'];

		$resSection = CIBlockSection::GetByID($fields['IBLOCK_SECTION_ID']);
		if (!$resSection || $resSection->SelectedRowsCount() !== 1) {
			ShowError(GetMessage('SECTION_NOT_FOUND',
				array("#ELEMENT_ID#" => $fields['ID'])));
			CHTTP::SetStatus('500 Internal Server Error');
			$this->AbortResultCache();
			return;
		}
		$arSection = $resSection->GetNext();

		$item['LINK_TITLE'] = $arSection['NAME'].'. '.$item['ART'];
		$item['DETAIL_PAGE_URL'] = $arIBlock['LIST_PAGE_URL'];
		if (is_array($arResult['FOR_PAGE'])) {
			$item['DETAIL_PAGE_URL'] .= $arResult['FOR_PAGE']['CODE'].'/';
		}
		$item['DETAIL_PAGE_URL'] .= $arSection['CODE'].'/'.$fields['CODE'].'.html';

		// shop
		$shopId = $props['SHOP']['VALUE'];
		$item['SHOP'] = null;
		if ($shopId) {
			$resShop = CIBlockElement::GetList(
				array(),
				array(
					'IBLOCK_ID' => 1,
					'ID' => $shopId,
				));
			if ($arResShop = $resShop->GetNextElement()) {
				$arResFShop = $arResShop->GetFields();
				if ($arResFShop['ID'] == $shopId) {
					$arProp = $arResShop->GetProperties();
					$item['SHOP'] = array(
						'NAME' => $arResFShop['NAME'],
						'~NAME' => $arResFShop['~NAME'],
						'PHONE' => $arProp['PHONE']['VALUE'],
						'~PHONE' => $arProp['PHONE']['~VALUE'],
					);
				}
			}
		}

		// fix preview text
		if (!preg_match('/<p>/', $item['PREVIEW_TEXT'])) {
			$item['PREVIEW_TEXT'] = '<p>'.$item['PREVIEW_TEXT'].'</p>';
		}

		$arResult['ITEMS'][] = $item;
	}

	$arResult["NAV_STRING"] = $res->GetPageNavStringEx(
		$navComponentObject, $arParams["PAGER_TITLE"],
		$arParams["PAGER_TEMPLATE"], $arParams["PAGER_SHOW_ALWAYS"]);
	$arResult["NAV_CACHED_DATA"] = $navComponentObject->GetTemplateCachedData();
	$arResult["NAV_RESULT"] = $res;

	if ($arResult["NAV_RESULT"]->NavPageCount <= 1 && !$arParams["PAGER_SHOW_ALWAYS"]) {
		$arParams["DISPLAY_TOP_PAGER"] = false;
		$arParams["DISPLAY_BOTTOM_PAGER"] = false;
	}

	// seo data for first page
	$arResult["DESCRIPTION_LEFT"] = null;
	$arResult["DESCRIPTION_RIGHT"] = null;
	if (
		$arResult["NAV_RESULT"]->NavPageNomer == 1 &&
		!empty($arParams['SECTION_CODE'])
	) {
		$sectionRes = CIBlockSection::GetList(
			array(),
			array(
				"ACTIVE" => "Y",
				"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
				"IBLOCK_ID" => $arParams['IBLOCK_ID'],
				"CODE" => $arParams['SECTION_CODE'],
			),
			false,
			array("UF_SEO"));
		$arSection = $sectionRes->GetNext();
		$seoElId = $arSection["UF_SEO"];

		if ($seoElId) {
			// get seo description
			$res = CIBlockElement::GetList(
				array(),
				array(
					"IBLOCK_ID" => 6,
					"ID" => $seoElId,
				));
			if ($arRes = $res->GetNextElement()) {
				$arResF = $arRes->GetFields();
				$arResult['SECTION_NAME'] = $arResF['NAME'];
				if ($arResF["ID"] == $seoElId) {
					$arProp = $arRes->GetProperties();
					foreach ($arProp as $key=>$val) {
						$arProp[$key]["DISPLAY_VALUE"] = textOrHtmlValue($val["VALUE"]);
					}
					$arResult["DESCRIPTION_LEFT"] = $arProp["DESC_L"]["DISPLAY_VALUE"];
					$arResult["DESCRIPTION_RIGHT"] = $arProp["DESC_R"]["DISPLAY_VALUE"];
				}
			}
		}
	}

	$this->SetResultCacheKeys(array(
		"ID",
		"NAV_CACHED_DATA",
	));
	$this->IncludeComponentTemplate();

}

$this->SetTemplateCachedData($arResult["NAV_CACHED_DATA"]);
