<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$show = $_GET["show"] ? $_GET["show"] : false;
$itemCount = 6;
$onPage = count($arResult["ITEMS"]);
global $arrFilter, $currentBrendID;
?>

<ul class="collection_list brand">
<?foreach($arResult["ITEMS"] as $Item){?>
	<li id="bx_id_<?=$Item["ID"]?>">
		<?if($Item["PREVIEW_PICTURE"]){?>
			<a href="<?=$Item["DETAIL_PICTURE"]["SRC"]?>" class="preview">
				<img alt="<?=$Item["PREVIEW_PICTURE"]["DESCRIPTION"]
					?>" src="<?=$Item["PREVIEW_PICTURE"]["SRC"]?>" />
			</a>
		<?}?>
		<?if($Item["PREVIEW_TEXT"]){?>
			<div class="info detail">
				<div class="text">
					<p><?=GetMessage("ART.")?>&nbsp;<?=$Item["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]?></p>
					<?=$Item["PREVIEW_TEXT"]?>
					<?if($Item["DISPLAY_PROPERTIES"]["SHOP"]["VALUE"]){?>
						<?$shop = CIBlockElement::GetById($Item["DISPLAY_PROPERTIES"]["SHOP"]["VALUE"]);
						$arShop = $shop->GetNextElement();
						$shopFields = $arShop->GetFields();
						$shopProps = $arShop->GetProperties();?>
						<p><?=$shopFields["NAME"]?><br /><?=$shopProps["PHONE"]["VALUE"]?></p>
					<?}?>
				</div>
				<?if($Item["DETAIL_PICTURE"]){?>
					<img class="picture" alt="<?=$Item["DETAIL_PICTURE"]["DESCRIPTION"]
						?>" src="<?=$Item["DETAIL_PICTURE"]["SRC"]?>" />
				<?}?>
			</div>
			<div class="info hover">
				<div class="text">
					<?=$Item["PREVIEW_TEXT"]?>
				</div>
			</div>
		<?}?>
	</li>
<?}?>
</ul>

<?$arFilter = array(
	"ACTIVE" => "Y",
	"IBLOCK_ID" => $arResult["ID"],
);
if($arResult["SECTION"]["PATH"][0]["ID"]){
	$arFilter["SECTION_ID"] = $arResult["SECTION"]["PATH"][0]["ID"];
}else{
	$path = $APPLICATION->GetCurPage();
	$path = explode("/", $path);

	if(count($path) >= 4 && $path[3]){
		$section_code = $path[3];

		$rs_section = CIBlockSection::GetList(
			array(),
			array(
				"IBLOCK_TYPE" => "lists",
				"IBLOCK_CODE" => "catalog",
				"CODE" => $section_code
			)
		);
		$ar_secrion = $rs_section->GetNext();

		$arFilter["SECTION_ID"] = $ar_secrion["ID"];
	}
}
if($arrFilter["PROPERTY_FOR"]){
	$arFilter["PROPERTY_FOR"] = $arrFilter["PROPERTY_FOR"];
}
$arFilter["PROPERTY_BRAND"] = $currentBrendID;

$total = CIBlockElement::GetList(
	array(),
	$arFilter,
	false,
	array(),
	array()
);
$totalcount = $total->SelectedRowsCount();

if(count($arResult["ITEMS"]) && count($arResult["ITEMS"]) < $totalcount){?>
	<a class="load_more" title="<?=GetMessage("SHOW_MORE")
		?>" data-next-page="2" data-count="<?=$itemCount
		?>" data-iblock-section="<?=$arFilter["SECTION_ID"]
		?>" data-brand="<?=$currentBrendID
		?>" data-for="<?=$arrFilter["PROPERTY_FOR"]
		?>" data-iblock="catalog"><span><?=GetMessage("SHOW_MORE")
		?></span></a>
<?}?>
