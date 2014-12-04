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
