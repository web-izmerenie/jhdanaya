<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$json["status"] = "error";

CModule::IncludeModule("iblock");

$iblock = CIBlock::GetList(
	array(),
	array(
		"TYPE" => "lists",
		"CODE" => 'events',
	)
);
$arIBlock = $iblock->Fetch();

$arFilter = array(
	"ACTIVE" => "Y",
	"IBLOCK_ID" => $arIBlock["ID"],
);
$arOrder = array(
    "active_from" => "desc"
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

		$item = array();

		$item["id"] = "bx_id_".$arResF["ID"];

		$item["title"] = $arResF["NAME"];

		$item["date"] = CIBlockFormatProperties::DateFormat(
			'j F',
			strtotime($arResF["DATE_ACTIVE_FROM"]));

		if ($arResF["PREVIEW_TEXT"])
			$item["preview_text"] = $arResF["PREVIEW_TEXT"];

		$gall = $arResP["GALLERY"]['VALUE'];
		$previewPhotos = array();

		if ($arResF["DETAIL_TEXT"] || ($gall && count($gall) > 4)) {
			$item["link"] = $arResF["DETAIL_PAGE_URL"];
		}

		if ($gall) {
			for($i = 0; $i<4, $i<count($gall); $i++){
				$arImg = CFile::GetByID($gall[$i]);
				$arImg = $arImg->Fetch();
				$thumb = CFile::ResizeImageGet(
					$gall[$i],
					array("width" => "233", "height" => "233"),
					BX_RESIZE_IMAGE_EXACT);
				$previewPhotos[] = array(
					'src' => $thumb['src'],
					'description' => $arImg['DESCRIPTION'],
					'width' => 233,
					'height' => 233,
				);
			}
			$item["preview_photos"] = $previewPhotos;
		}

		$items[] = $item;

		$currentcount++;
	}
	if ($items) $json["items"] = $items;
}

header('Content-Type: application/json; charset=utf-8');

echo json_encode($json);

?>
