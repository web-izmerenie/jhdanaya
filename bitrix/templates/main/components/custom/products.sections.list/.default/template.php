<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<ul class="collection_list produce">
	<?foreach($arResult["ITEMS"] as $arSection):?>
		<li id="bx_id_<?=$arSection["ID"]?>">
			<a href="<?=$arSection["SECTION_PAGE_URL"]?>" title="<?=$arSection["NAME"]?>">
				<span><img alt="<?=$arSection['PICTURE']["DESCRIPTION"]
					?>" src="<?=$arSection['PICTURE']["SRC"]
					?>" width="<?=$arSection['PICTURE']["WIDTH"]
					?>" height="<?=$arSection['PICTURE']["HEIGHT"]
					?>" /></span>
				<span><?=$arSection["NAME"]?></span>
			</a>
		</li>
	<?endforeach?>
</ul>

<?if(!empty($arResult['FOR_LIST_ACTIVE']) && !$arResult['FOR_PAGE']):?>
	<ul class="produce_submenu">
		<?foreach($arResult['FOR_LIST_ACTIVE'] as $arItem):?>
			<li><a href="<?=$arItem['LINK']?>"><?=$arItem['NAME']?></a></li>
		<?endforeach?>
	</ul>
<?endif?>
