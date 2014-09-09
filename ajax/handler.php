<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/main/header.php");

$json["status"] = "error";

//$json["dedug"] = $_GET;

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
$arOrder = array(
    "SORT" => "asc"
);
$total = CIBlockElement::GetList(
	$arOrder,
	$arFilter,
	false,
	array(),
	array()
);
$totalcount = $total->SelectedRowsCount();

function getRes($arOrder, $arFilter, $page, $count) {
	return CIBlockElement::GetList(
		$arOrder,
		$arFilter,
		false,
		array(
			"iNumPage" => $page,
			"nPageSize" => $count,
		),
		array()
	);
}

$res = getRes($arOrder, $arFilter, $_GET["page"], $_GET["count"]);
$pagecount = $res->SelectedRowsCount();
$currentcount = ($_GET["page"] - 1) * $_GET["count"];
$futurecount = ($_GET["page"]) * $_GET["count"];

if($pagecount){
	$json["status"] = "success";
	$items = array();
	while($arRes = $res->GetNextElement()){
		$arResF = $arRes->GetFields();
		$arResP = $arRes->GetProperties();

		if($currentcount >= $totalcount){
			$json["status"] = "end_of_list";
			break;
		}elseif($futurecount >= $totalcount){
			$json["status"] = "end_of_list";
		}

		$previewPicture = CFile::GetByID($arResF["PREVIEW_PICTURE"]);
		$previewPicture = $previewPicture->Fetch();
		$previewPicture["SRC"] = CFile::GetPath($arResF["PREVIEW_PICTURE"]);
		$detailPicture = CFile::GetByID($arResF["DETAIL_PICTURE"]);
		$detailPicture = $detailPicture->Fetch();
		$detailPicture["SRC"] = CFile::GetPath($arResF["DETAIL_PICTURE"]);

		$shop = CIBlockElement::GetById($arResP["SHOP"]["VALUE"]);
		if($shop->SelectedRowsCount()){
			$arShop = $shop->GetNextElement();
			$shopFields = $arShop->GetFields();
			$shopProps = $arShop->GetProperties();
		}
		$art = "";
		if($arResP["ARTICLE"]["VALUE"]){
			$art = "<p>".GetMessage("ART.")."&nbsp;".
				$arResP["ARTICLE"]["VALUE"]."</p>";
		}
		$items[$_GET["iblock"]][] = array(
			"id" => "bx_id_".$arResF["ID"],
			"preview" => array(
				"description" => $arResF["NAME"],
				"src" => $previewPicture["SRC"],
				"width" => $previewPicture["WIDTH"],
				"height" => $previewPicture["HEIGHT"],
				"description" => $previewPicture["DESCRIPTION"],
			),
			"info_detail" => array(
				"text" => $art.$arResF["PREVIEW_TEXT"].
					"<p>".$shopFields["NAME"]."<br />".$shopProps["PHONE"]["VALUE"]."</p>",
				"picture" => array(
					"description" => $arResF["NAME"],
					"src" => $detailPicture["SRC"],
					"width" => $detailPicture["WIDTH"],
					"height" => $detailPicture["HEIGHT"],
					"description" => $detailPicture["DESCRIPTION"],
				)
			),
			"info_hover" => array(
				"text" => $art.$arResF["PREVIEW_TEXT"],
			),
		);

		$currentcount++;
	}
}
$json["items"] = $items[$_GET["iblock"]];

header('Content-Type: application/json; charset=utf-8');

echo json_encode($json);

?>
