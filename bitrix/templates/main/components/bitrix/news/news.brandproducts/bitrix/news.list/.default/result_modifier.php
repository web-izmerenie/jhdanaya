<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

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
}
