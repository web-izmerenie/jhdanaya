<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$json["dedug"] = $_GET;

CModule::IncludeModule("iblock");
$iblock = CIBlock::GetList(
	array(),
	array(
		"TYPE" => "lists",
		"CODE" => $_GET["iblock"]
	)
);
$arIBlock = $iblock->Fetch();

$arFilter = array(
	"ACTIVE" => "Y",
	"IBLOCK_ID" => $arIBlock["ID"],
);
if(!$_GET["brand"]){
	$arFilter["PROPERTY_BRAND"] = false;
}else{
	$arFilter["PROPERTY_BRAND"] = $_GET["brand"];
}
if($_GET["section"]){
	$arFilter["SECTION_ID"] = $_GET["section"];
}
if($_GET["datafor"]){
	$arFilter["PROPERTY_FOR"] = $_GET["datafor"];
}
$total = CIBlockElement::GetList(
	array(),
	$arFilter,
	false,
	array(),
	array()
);
$totalcount = $total->SelectedRowsCount();

$res = CIBlockElement::GetList(
	array(
		"ID" => "desc"
	),
	$arFilter,
	false,
	array(
		"iNumPage" => $_GET["page"],
		"nPageSize" => $_GET["count"]
	),
	array()
);
$pagecount = $res->SelectedRowsCount();
$currentcount = ($_GET["page"] - 1) * $_GET["count"];

if($pagecount){
	$json["status"] = "success";
	$items = array();
	while($arRes = $res->GetNextElement()){
		$arResF = $arRes->GetFields();
		$arResP = $arRes->GetProperties();
		if($currentcount >= $totalcount){
			$json["status"] = "end_of_list";
			break;
		}
		$sizeParams = $_GET["brand"] ? array("width" => "288", "height" => "288") : array("width" => "231", "height" => "126");
		$thumb = CFile::ResizeImageGet($arResF["PREVIEW_PICTURE"], $sizeParams, BX_RESIZE_IMAGE_PROPORTIONAL);

		$shop = CIBlockElement::GetById($arResP["SHOP"]["VALUE"]);
		if($shop->SelectedRowsCount()){
			$arShop = $shop->GetNextElement();
			$shopFields = $arShop->GetFields();
			$shopProps = $arShop->GetProperties();
		}
		$items[$_GET["iblock"]][] = array(
			"id" => "bx_id_".$arResF["ID"],
			"preview" => array(
				"description" => $arResF["NAME"],
				"src" => $thumb["src"]
			),
			"info_detail" => array(
				"text" => "
				<p>Арт. ".$arResP["ARTICLE"]["VALUE"]."</p>
				".$arResF["PREVIEW_TEXT"]."
				<p>".$shopFields["NAME"]."<br />".$shopProps["PHONE"]["VALUE"]."</p>",
				"picture" => array(
					"description" => $arResF["NAME"],
					"src" => $thumb["src"]
				)
			),
			"info_hover" => array(
				"text" => $arResF["PREVIEW_TEXT"],
			),
		);

		$currentcount++;
	}
}
$json["items"] = $items[$_GET["iblock"]];

header('Content-Type: application/json; charset=utf-8');

echo json_encode($json);

?>
