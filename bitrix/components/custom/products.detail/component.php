<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule("iblock");

$noCacheCallback = function ($APPLICATION, $arResult) {
	$meta = $arResult['IPROPERTY_VALUES'];

	if (!empty($meta['ELEMENT_META_TITLE']))
		$APPLICATION->SetPageProperty("title", $meta['ELEMENT_META_TITLE']);
	if (!empty($meta['ELEMENT_META_DESCRIPTION']))
		$APPLICATION->SetPageProperty("description", $meta['ELEMENT_META_DESCRIPTION']);
	if (!empty($meta['ELEMENT_META_KEYWORDS']))
		$APPLICATION->SetPageProperty("keywords", $meta['ELEMENT_META_KEYWORDS']);
	if (!empty($meta['ELEMENT_PAGE_TITLE']))
		$APPLICATION->SetTitle($meta['ELEMENT_PAGE_TITLE']);
};

require $_SERVER['DOCUMENT_ROOT'].'/inc/get_for_list.php';

if ($this->StartResultCache(false)) {

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
	$arSection = $resSection->GetNext();

	$res = CIBlockElement::GetList(
		array(),
		array_merge(
			array(
				"ACTIVE" => "Y",
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"CODE" => $arParams["ELEMENT_CODE"],
			),
			$additionalFilter
		),
		false,
		array("nPageSize" => 1),
		array(
			"IBLOCK_TYPE", "IBLOCK_ID", "ID", "CODE", "NAME",
			"PREVIEW_TEXT", "DETAIL_PICTURE", "IBLOCK_SECTION_ID"
		)
	);
	$arElement = $res->GetNextElement();
	$arResult = $arElement->GetFields();
	if (
		!$arElement ||
		$arResult['CODE'] !== $arParams['ELEMENT_CODE'] ||
		$arResult['IBLOCK_SECTION_ID'] !== $arSection['ID']
	) {
		ShowError(GetMessage("ELEMENT_NOT_FOUND"));
		@define("ERROR_404", "Y");
		CHTTP::SetStatus("404 Not Found");
		$this->AbortResultCache();
	} else {
		$props = $arElement->GetProperties();

		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues(
			$arResult["IBLOCK_ID"], $arResult["ID"]);
		$arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();

		// preview text
		if (!preg_match('/<p>/', $arResult['PREVIEW_TEXT'])) {
			$arResult['PREVIEW_TEXT'] = '<p>'.$arResult['PREVIEW_TEXT'].'</p>';
		}

		// article
		$arResult['ART'] = GetMessage('ART.').'&nbsp;'
			.$props['ARTICLE']['VALUE'];

		// picture
		$arPic = CFile::GetFileArray($arResult['DETAIL_PICTURE']);
		$image = array(
			'description' => $arPic['DESCRIPTION'],
			'src' => $arPic['SRC'],
			'width' => $arPic['WIDTH'],
			'height' => $arPic['HEIGHT'],
		);
		$arResult['PICTURE'] = $image;

		// shop
		$shopId = $props['SHOP']['VALUE'];
		$arResult['SHOP'] = null;
		if ($shopId) {
			$res = CIBlockElement::GetList(
				array(),
				array(
					'IBLOCK_ID' => 1,
					'ID' => $shopId,
				));
			if ($arRes = $res->GetNextElement()) {
				$arResF = $arRes->GetFields();
				if ($arResF['ID'] == $shopId) {
					$arProp = $arRes->GetProperties();
					$arResult['SHOP'] = array(
						'NAME' => $arResF['NAME'],
						'~NAME' => $arResF['~NAME'],
						'PHONE' => $arProp['PHONE']['VALUE'],
						'~PHONE' => $arProp['PHONE']['~VALUE'],
					);
				}
			}
		}

		// additional description fields
		$arResult["DESCRIPTION_LEFT"] =
			textOrHtmlValue($props['DESC_L']['VALUE']);
		$arResult["DESCRIPTION_RIGHT"] =
			textOrHtmlValue($props['DESC_R']['VALUE']);

		// back to list link
		$arResult['BACK_TO_LIST_LINK'] = $arResult['SECTION']['PATH'][0]['SECTION_PAGE_URL'];
		if (strrpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME']) !== false) {
			$arResult['BACK_TO_LIST_LINK'] = $_SERVER['HTTP_REFERER'];
		}

		$noCacheCallback(&$APPLICATION, &$arResult);
		$this->SetResultCacheKeys(array("IPROPERTY_VALUES"));
		$this->IncludeComponentTemplate();
	}

} else {
	$noCacheCallback(&$APPLICATION, &$arResult);
}
