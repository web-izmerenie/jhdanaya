<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<section class="events_list">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?$has_link = $arItem["DETAIL_TEXT"] || (count($arItem["DISPLAY_PROPERTIES"]["GALLERY"]["VALUE"]) > 4 ? true : false);?>
	<div class="event_item" itemscope itemtype="http://schema.org/NewsArticle">
		<h3 itemprop="headline">
			<?if($has_link){?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?}?>
				<span><?=$arItem["NAME"]?></span>
			<?if($has_link){?></a><?}?>
		</h3>

		<?$arItem["DISPLAY_ACTIVE_FROM"] = preg_split("/\s/", $arItem["DISPLAY_ACTIVE_FROM"]);?>
		<h4 itemprop="dateline"><?=$arItem["DISPLAY_ACTIVE_FROM"][0]?> <?=$arItem["DISPLAY_ACTIVE_FROM"][1]?></h4>

		<?if($arItem["PREVIEW_TEXT"]){?>
			<div class="preview_text" itemprop="description">
				<?=$arItem["PREVIEW_TEXT"]?>
			</div>
		<?}?>

		<?if($arItem["DISPLAY_PROPERTIES"]["GALLERY"]["VALUE"]){?>
			<ul class="preview_photos">
				<?$ar_ids = $arItem["DISPLAY_PROPERTIES"]["GALLERY"]["VALUE"];?>
				<?for($i = 0; $i < 4; $i++){?>
					<?if(!$ar_ids[$i])break;?>
					<?$thumb = CFile::ResizeImageGet($ar_ids[$i], array("width" => "233", "height" => "233"), BX_RESIZE_IMAGE_EXACT);?>
					<li><img alt="" src="<?=$thumb["src"]?>" itemprop="image" /></li>
				<?}?>
			</ul>
		<?}?>
	</div>
<?endforeach;?>

<?
$total = CIBlockElement::GetList(
	array(),
	array( // filter
		"ACTIVE" => "Y",
		"IBLOCK_ID" => $arResult["ID"],
	),
	false,
	array(),
	array()
);
$totalcount = $total->SelectedRowsCount();

if(count($arResult["ITEMS"]) && count($arResult["ITEMS"]) < $totalcount){?>
	<a class="load_more" title="<?=GetMessage("SHOW_MORE")
		?>" data-next-page="2" data-count="<?=count($arResult["ITEMS"])
		?>"><span><?=GetMessage("SHOW_MORE")?></span></a>
<?}?>


</section>
