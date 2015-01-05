<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(is_array($arResult["SECTION"])):?>

	<?if($arParams["DISPLAY_TOP_PAGER"]):?>
		<div class="pagination top">
			<?=$arResult["NAV_STRING"]?>
		</div>
	<?endif?>

	<ul class="collection_list rings products">
		<?foreach($arResult["ITEMS"] as $Item):?>
			<li id="bx_id_<?=$Item["ID"]?>">
				<?
				$ART =
					GetMessage("ART.").'&nbsp;'
					.$Item["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"];
				$linkTitle = $arResult['SECTION_NAME'].'. '.$ART;
				?>
				<a href="<?=$Item["DETAIL_PAGE_URL"]?>" title="<?=$ART
					?>" class="detail_page"><?=$linkTitle?></a>
				<div class="preview">
					<img alt="<?=$Item["PREVIEW_PICTURE"]["DESCRIPTION"]
						?>" src="<?=$Item["PREVIEW_PICTURE"]["SRC"]?>" />
				</div>
				<div class="info detail">
					<div class="text">
						<?if($Item["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]){?>
							<p><?=$ART?></p>
						<?}?>
						<?=$Item["PREVIEW_TEXT"]?>
						<?if($Item['SHOP']):?>
							<p class="shop_info">
								<?=$Item['SHOP']['NAME']?>
								<?if($Item['SHOP']['PHONE']):?>
									<br/>
									<?=GetMessage('PHONE')?>
									<?=$Item['SHOP']['PHONE']?>
								<?endif?>
							</p>
						<?endif?>
					</div>
					<?if($Item["DETAIL_PICTURE"]){?>
						<img class="picture" alt="<?=$Item["DETAIL_PICTURE"]["DESCRIPTION"]
							?>" src="<?=$Item["DETAIL_PICTURE"]["SRC"]?>" />
					<?}?>
				</div>
			</li>
		<?endforeach?>
	</ul>

	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<div class="pagination bottom">
			<?=$arResult["NAV_STRING"]?>
		</div>
	<?endif?>

	<?if($arResult["DESCRIPTION_LEFT"] || $arResult["DESCRIPTION_RIGHT"]):?>
		<?$subClass = ($arResult["DESCRIPTION_LEFT"] && $arResult["DESCRIPTION_RIGHT"]) ? ' double' : ''?>
		<div class="description<?=$subClass?>">
			<div class="column left">
				<?=$arResult["DESCRIPTION_LEFT"]?>
			</div>
			<div class="column right">
				<?=$arResult["DESCRIPTION_RIGHT"]?>
			</div>
		</div>
	<?endif?>

<?endif?>
