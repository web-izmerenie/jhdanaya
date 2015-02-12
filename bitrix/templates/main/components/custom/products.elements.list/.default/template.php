<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<div class="pagination top">
		<ul class="pages_list">
			<?foreach($arResult['PAGINATION_NAV'] as $arItem):?>
				<li>
					<?if($arItem['CURRENT']):?>
						<b><?=$arItem['NUM']?></b>
					<?else:?>
						<a href="<?=$arItem['LINK']?>"><?=$arItem['NUM']?></a>
					<?endif?>
				</li>
			<?endforeach?>
		</ul>
	</div>
<?endif?>

<ul class="collection_list rings products">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<li id="bx_id_<?=$arItem["ID"]?>">
			<a href="<?=$arItem["DETAIL_PAGE_URL"]
				?>" title="<?=$arItem['ART']
				?>" class="detail_page"><?=$arItem['LINK_TITLE']?></a>
			<div class="preview">
				<img alt="<?=$arItem["PREVIEW_PICTURE"]["DESCRIPTION"]
					?>" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" />
			</div>
			<div class="info detail">
				<div class="text">
					<?if($arItem["ARTICLE"]){?>
						<p><?=$arItem['ART']?></p>
					<?}?>
					<?=$arItem["PREVIEW_TEXT"]?>
					<?if($arItem['SHOP']):?>
						<!--noindex-->
						<p class="shop_info">
							<?=$arItem['SHOP']['NAME']?>
							<?if($arItem['SHOP']['PHONE']):?>
								<br/>
								<?=GetMessage('PHONE')?>
								<?=$arItem['SHOP']['PHONE']?>
							<?endif?>
						</p>
						<!--/noindex-->
					<?endif?>
				</div>
				<?if($arItem["DETAIL_PICTURE"]){?>
					<img class="picture" alt="<?=$arItem["DETAIL_PICTURE"]["DESCRIPTION"]
						?>" src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" />
				<?}?>
			</div>
		</li>
	<?endforeach?>
</ul>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<div class="pagination bottom">
		<ul class="pages_list">
			<?foreach($arResult['PAGINATION_NAV'] as $arItem):?>
				<li>
					<?if($arItem['CURRENT']):?>
						<b><?=$arItem['NUM']?></b>
					<?else:?>
						<a href="<?=$arItem['LINK']?>"><?=$arItem['NUM']?></a>
					<?endif?>
				</li>
			<?endforeach?>
		</ul>
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
