<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$show = $_GET["show"] ? $_GET["show"] : false;
$itemCount = 6;
$onPage = count($arResult["ITEMS"]);
global $arrFilter, $currentBrendID;
?>

<?if(is_array($arResult["SECTION"]) || $show === "all"){?>

	<ul class="collection_list rings">
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

	<?
	$arFilter = array(
		"ACTIVE" => "Y",
		"IBLOCK_ID" => $arResult["ID"],
	);
	if($arResult["SECTION"]["PATH"][0]["ID"]){
		$arFilter["SECTION_ID"] = $arResult["SECTION"]["PATH"][0]["ID"];
	}
	if($arrFilter["PROPERTY_FOR"]){
		$arFilter["PROPERTY_FOR"] = $arrFilter["PROPERTY_FOR"];
	}
	$arFilter["PROPERTY_BRAND"] = false;

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
			?>" data-iblock-section="<?=$arResult["SECTION"]["PATH"][0]["ID"]
			?>" data-brand="" data-for="<?=$arrFilter["PROPERTY_FOR"]
			?>" data-iblock="catalog"><span><?=GetMessage("SHOW_MORE")
			?></span></a>
	<?}?>

<?}else{?>

	<?
	$sFilter = array(
		"IBLOCK_ID" => $arResult["ID"]
	);
	if($arrFilter["PROPERTY_FOR"]){
		$sFilter["PROPERTY"] = array(
			"FOR" => $arrFilter["PROPERTY_FOR"],
			"BRAND" => $arrFilter["PROPERTY_BRAND"]
		);
	}
	$section = CIBlockSection::GetList(
		array(
			"SORT" => "asc"
		),
		$sFilter
	);
	if($section->SelectedRowsCount()){?>
		<ul class="collection_list produce">
			<?while($arSection = $section->GetNext()){?>
				<?
				if(stripos($APPLICATION->GetCurDir(), "/women/"))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/women", $arSection["SECTION_PAGE_URL"]);
				if(stripos("/men/", $APPLICATION->GetCurDir()))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/men", $arSection["SECTION_PAGE_URL"]);
				if(stripos("/children/", $APPLICATION->GetCurDir()))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/children", $arSection["SECTION_PAGE_URL"]);
				?>
				<li id="bx_id_<?=$arSection["ID"]?>">
					<?
					$arFile = CFile::GetFileArray($arSection["PICTURE"]);
					if($arFile["WIDTH"] > 296){
						$thumb = CFile::ResizeImageGet($arSection["PICTURE"], array("width" => "296", "height" => "159"), BX_RESIZE_IMAGE_PROPORTIONAL);
					}else{
						$thumb["src"] = CFile::GetPath($arSection["PICTURE"]);
					}
					?>
					<a href="<?=$arSection["SECTION_PAGE_URL"] . $urlsuffix?>" title="<?=$arSection["NAME"]?>">
						<span><img alt="<?=$arSection["NAME"]?>" src="<?=$thumb["src"]?>" /></span>
						<span><?=$arSection["NAME"]?></span>
					</a>
				</li>
			<?}?>
		</ul>
	<?}?>

	<ul class="produce_submenu">
		<?$path = $APPLICATION->GetCurDir()?>
		<li><a href="<?=$path?>women/"><?=GetMessage("FOR_WOMEN")?></a></li>
		<li><a href="<?=$path?>men/"><?=GetMessage("FOR_MEN")?></a></li>
		<li><a href="<?=$path?>children/"><?=GetMessage("FOR_CHILDREN")?></a></li>
	</ul>

<?}?>
