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
			if ($arResF["ID"] == $seoElId) {
				$arProp = $arRes->GetProperties();
				foreach ($arProp as $key=>$val) {
					if (strtolower($val["VALUE"]["TYPE"]) == "text") {
						$newVal = trim($val["VALUE"]["TEXT"]);
						$newVal = htmlspecialcharsex($newVal);
						$newVal = preg_replace("/\r\n/", "\n", $newVal);
						$newVal = preg_replace("/\r/", "\n", $newVal);
						$newVal = preg_replace("/\n[\n]+/", "\n\n", $newVal);
						$newVal = str_replace("\n\n", "</p><p>", $newVal);
						$newVal = str_replace("\n", "<br/>", $newVal);
						if (!empty($newVal)) $newVal = '<p>'.$newVal.'</p>';
						$arProp[$key]["DISPLAY_VALUE"] = $newVal;
					} elseif (strtolower($val["VALUE"]["TYPE"]) == "html") {
						$arProp[$key]["DISPLAY_VALUE"] = trim($val["VALUE"]["TEXT"]);
					}
				}
				$arResult["DESCRIPTION_LEFT"] = $arProp["DESC_L"]["DISPLAY_VALUE"];
				$arResult["DESCRIPTION_RIGHT"] = $arProp["DESC_R"]["DISPLAY_VALUE"];
			}
		}
	}
}
