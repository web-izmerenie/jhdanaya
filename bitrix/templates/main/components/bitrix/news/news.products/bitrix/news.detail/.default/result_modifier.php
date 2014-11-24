<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!preg_match("/<p>/", $arResult["PREVIEW_TEXT"])) {
	$arResult["PREVIEW_TEXT"] = '<p>'.$arResult["PREVIEW_TEXT"].'</p>';
}

$arResult['ART'] = GetMessage("ART.").'&nbsp;'
	.$arResult["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"];

$image = CFile::ResizeImage(
	$arResult['DETAIL_PICTURE'],
	array(
		"width" => "720",
		"height" => "720",
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
