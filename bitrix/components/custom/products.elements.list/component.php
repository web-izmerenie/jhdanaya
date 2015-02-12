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

$noCacheCallback = function ($APPLICATION, $arResult) {
	// seo hack (see header.php in "main" template)
	global $ALLOWED_PAGER_KEYS;
	if (is_array($ALLOWED_PAGER_KEYS)) {
		$ALLOWED_PAGER_KEYS[] = $arResult['PAGINATION_VAR_NAME'];
	}

	$meta = $arResult['IPROPERTY_VALUES'];

	if (!is_array($meta)) return;

	if (!empty($meta['SECTION_META_TITLE']))
		$APPLICATION->SetPageProperty("title", $meta['SECTION_META_TITLE']);
	if (!empty($meta['SECTION_META_DESCRIPTION']))
		$APPLICATION->SetPageProperty("description", $meta['SECTION_META_DESCRIPTION']);
	if (!empty($meta['SECTION_META_KEYWORDS']))
		$APPLICATION->SetPageProperty("keywords", $meta['SECTION_META_KEYWORDS']);
	if (!empty($meta['SECTION_PAGE_TITLE']))
		$APPLICATION->SetTitle($meta['SECTION_PAGE_TITLE']);
};

if ($this->StartResultCache(false)) {

	// pagination stuff
	$arResult['PAGINATION_VAR_NAME'] = $arParams['PAGINATION_VAR_NAME'];
	if (ctype_digit((string)$_GET[$arResult['PAGINATION_VAR_NAME']])) {
		$arResult['PAGE_NUM'] = (int)$_GET[$arResult['PAGINATION_VAR_NAME']];
	} else if (array_key_exists($arResult['PAGINATION_VAR_NAME'], $_GET)) {
		ShowError(GetMessage('INCORRECT_PAGE_NUM'));
		CHTTP::SetStatus('400 Bad Request');
		$noCacheCallback(&$APPLICATION, &$arResult);
		$this->AbortResultCache();
		return;
	} else {
		$arResult['PAGE_NUM'] = 1;
	}

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
		$noCacheCallback(&$APPLICATION, &$arResult);
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
			$noCacheCallback(&$APPLICATION, &$arResult);
			$this->AbortResultCache();
			return;
		}
		$arSection = $resSection->GetNext();

		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(
			$arParams["IBLOCK_ID"], $arSection["ID"]);
		$arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();
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
		array(
			"nPageSize" => (int)$arParams['PAGINATION_COUNT'],
			"iNumPage" => $arResult['PAGE_NUM'],
		),
		null
	);

	// other pagination stuff
	$arResult['TOTAL_COUNT'] = $res->nSelectedCount;
	$arResult['TOTAL_PAGES'] = ceil(
		$arResult['TOTAL_COUNT'] / (int)$arParams['PAGINATION_COUNT']
	);
	if (
		$arResult['PAGE_NUM'] <= 0 ||
		$arResult['PAGE_NUM'] > $arResult['TOTAL_PAGES']
	) {
		define('ERROR_404', 'Y');
		CHTTP::SetStatus("404 Not Found");
		ShowError(GetMessage('PAGE_NOT_FOUND'));
		$noCacheCallback(&$APPLICATION, &$arResult);
		$this->AbortResultCache();
		return;
	}

	$url = parse_url($_SERVER['REQUEST_URI']);
	$arResult['PAGINATION_NAV'] = array();
	for ($i=1; $i<=$arResult['TOTAL_PAGES']; $i++) {
		$link = $url['path'];
		if ($i === 1) {
			unset($_GET[$arResult['PAGINATION_VAR_NAME']]);
		} else {
			$_GET[$arResult['PAGINATION_VAR_NAME']] = $i;
		}
		if (!empty($_GET)) {
			$qs = array();
			foreach ($_GET as $key=>$val) {
				$qs[] = "{$key}={$val}";
			}
			$link .= '?'.implode('&', $qs);
		}
		$arResult['PAGINATION_NAV'][] = array(
			'NUM' => $i,
			'CURRENT' => ($arResult['PAGE_NUM'] === $i),
			'LINK' => $link,
		);
	}

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
			$noCacheCallback(&$APPLICATION, &$arResult);
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

	// seo data for first page
	$arResult["DESCRIPTION_LEFT"] = null;
	$arResult["DESCRIPTION_RIGHT"] = null;
	if (
		$arResult['PAGE_NUM'] === 1 &&
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

	$noCacheCallback(&$APPLICATION, &$arResult);
	$this->SetResultCacheKeys(array(
		"ID",
		"IPROPERTY_VALUES",
		"PAGE",
		"PAGINATION_VAR_NAME",
	));
	$this->IncludeComponentTemplate();

} else {
	$noCacheCallback(&$APPLICATION, &$arResult);
}
