<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule('iblock')) return;

$getProductsSections = function (
	$iblockType, $iblockID, $arSort,
	$curFor, $linkPrefix, $additionalFilter=null
) {
	// prepare filter
	$arFilter = array(
		"ACTIVE" => "Y",
		"IBLOCK_TYPE" => $iblockType,
		"IBLOCK_ID" => $iblockID,
	);

	// get active sections list
	// will be filtered more latear by active elements
	$res = CIBlockSection::GetList(
		$arSort,
		$arFilter,
		false,
		array('UF_*')
	);

	// prepare elements filter template
	$elFilterTmpl = $arFilter;
	if (is_array($curFor)) {
		$elFilterTmpl = array_merge($elFilterTmpl, array(
			'PROPERTY_FOR' => $curFor['CODE']
		));
	}
	if (is_array($additionalFilter)) {
		$elFilterTmpl = array_merge($elFilterTmpl, $additionalFilter);
	}

	$sectionsList = array();

	while ($arSection = $res->GetNext()) {
		// remove section from list if it hasn't any active elements
		$elFilter = array_merge(
			array('SECTION_ID' => $arSection['ID']), $elFilterTmpl);
		$elRes = CIBlockElement::GetList(array(), $elFilter);
		if ($elRes->SelectedRowsCount() <= 0) continue;

		// if we on page filtered by "FOR" then fix links for this page
		if (is_array($curFor)) {
			$arSection['SECTION_PAGE_URL'] = str_replace(
				$linkPrefix,
				$linkPrefix.$curFor['CODE'].'/',
				$arSection['SECTION_PAGE_URL']);
		}

		$sectionsList[] = $arSection;
	}

	return $sectionsList;
};
