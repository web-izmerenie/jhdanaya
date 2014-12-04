<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (
	is_array($arResult["SECTION"]) &&

	// show only on first page
	$arResult["NAV_RESULT"]->NavPageNomer == 1
) {
	$arResult["DESCRIPTION_LEFT"] = null;
	$arResult["DESCRIPTION_RIGHT"] = null;

	// get seo-element id by section user field
	$sectionId = $arResult["SECTION"]["PATH"][0]["ID"];
	$sectionRes = CIBlockSection::GetList(
		array(),
		array(
			"IBLOCK_ID" => $arResult["ID"],
			"ID" => $sectionId,
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

for ($i=0; $i<count($arResult["ITEMS"]); $i++) {
	$arItem = &$arResult["ITEMS"][$i];

	// shop
	$shopId = $arItem['DISPLAY_PROPERTIES']['SHOP']['VALUE'];
	$arItem['SHOP'] = null;
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
				$arItem['SHOP'] = array(
					'NAME' => $arResF['NAME'],
					'~NAME' => $arResF['~NAME'],
					'PHONE' => $arProp['PHONE']['VALUE'],
					'~PHONE' => $arProp['PHONE']['~VALUE'],
				);
			}
		}
	}

	// fix preview text
	if (!preg_match('/<p>/', $arItem['PREVIEW_TEXT'])) {
		$arItem['PREVIEW_TEXT'] = '<p>'.$arItem['PREVIEW_TEXT'].'</p>';
	}
}
