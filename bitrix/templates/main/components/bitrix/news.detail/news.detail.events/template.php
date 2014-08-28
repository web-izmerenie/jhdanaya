<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<section class="event_detail" itemscope itemtype="http://schema.org/NewsArticle">
	<?$arResult["DISPLAY_ACTIVE_FROM"] = preg_split("/\s/", $arResult["DISPLAY_ACTIVE_FROM"]);?>
	<div class="date" itemprop="dateline"><?=$arResult["DISPLAY_ACTIVE_FROM"][0]?> <?=$arResult["DISPLAY_ACTIVE_FROM"][1]?></div>

	<div class="preview_text" itemprop="description">
		<?if($arResult["DETAIL_TEXT"]):?>
			<?=$arResult["DETAIL_TEXT"]?>
		<?else:?>
			<?=$arResult["PREVIEW_TEXT"]?>
		<?endif;?>
	</div>

	<?if($arResult["DISPLAY_PROPERTIES"]["GALLERY"]["VALUE"]){?>
		<ul class="preview_photos">
			<?$ar_ids = $arResult["DISPLAY_PROPERTIES"]["GALLERY"]["VALUE"];?>
			<?foreach($ar_ids as $id){?>
				<?$thumb = CFile::ResizeImageGet($id, array("width" => "233", "height" => "233"), BX_RESIZE_IMAGE_EXACT);?>
				<li><a href="<?=CFile::GetPath($id)
					?>"><img alt="" src="<?=$thumb["src"]?>" itemprop="image" /></a></li>
			<?}?>
		</ul>
	<?}?>
</section>
