<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// preview text
if (!preg_match('/<p>/', $arResult['PREVIEW_TEXT'])) {
	$arResult['PREVIEW_TEXT'] = '<p>'.$arResult['PREVIEW_TEXT'].'</p>';
}

// article
$arResult['ART'] = GetMessage('ART.').'&nbsp;'
	.$arResult['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'];

// picture
$image = CFile::ResizeImage(
	$arResult['DETAIL_PICTURE'],
	array(
		'width' => '720',
		'height' => '720',
	),
	BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
if (!$image) {
	$image = array(
		'description' => $arResult['DETAIL_PICTURE']['DESCRIPTION'],
		'src' => $arResult['DETAIL_PICTURE']['SRC'],
		'width' => $arResult['DETAIL_PICTURE']['WIDTH'],
		'height' => $arResult['DETAIL_PICTURE']['HEIGHT'],
	);
}
$arResult['PICTURE'] = $image;

// shop
$shopId = $arResult['DISPLAY_PROPERTIES']['SHOP']['VALUE'];
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
