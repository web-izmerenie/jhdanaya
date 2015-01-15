<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$iblockType = 'lists';
$iblockID = 4;

header('Content-Type: text/plain; charset=utf-8');

// init requirements {{{1

$requiredModules = array('iblock', 'highloadblock');

foreach ($requiredModules as $requiredModule) {
	if (!CModule::IncludeModule($requiredModule)) {
		die('required module');
		return 0;
	}
}

// init requirements }}}1

require $_SERVER['DOCUMENT_ROOT'].'/inc/get_products_sections.php';

$arIBlock = GetIBlock($iblockID, $iblockType);

$additionalFilter = array('PROPERTY_BRAND' => false);

$sectionsList = $getProductsSections(
	$iblockType, $iblockID,
	array('SORT'=>'ASC'), false,
	$arIBlock['LIST_PAGE_URL'], $additionalFilter);

foreach ($sectionsList as $arSection) {
	$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($iblockID, $arSection['ID']);
	$meta = $ipropValues->getValues();
	if (empty($meta['SECTION_META_TITLE'])) continue;
	echo "$arSection[SECTION_PAGE_URL];$meta[SECTION_META_TITLE];$arSection[TIMESTAMP_X]\n";
}

$res = CIBlockElement::GetList(
	array('SORT'=>'ASC'),
	array_merge(
		array(
			"ACTIVE" => "Y",
			"IBLOCK_TYPE" => $iblockType,
			"IBLOCK_ID" => $iblockID,
		),
		$additionalFilter
	),
	false,
	array('nPageSize' => 10000));

while ($arItem = $res->GetNext()) {
	$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($iblockID, $arItem['ID']);
	$meta = $ipropValues->getValues();
	if (empty($meta['ELEMENT_META_TITLE'])) continue;
	echo "$arItem[DETAIL_PAGE_URL];$meta[ELEMENT_META_TITLE];$arItem[TIMESTAMP_X]\n";
}
