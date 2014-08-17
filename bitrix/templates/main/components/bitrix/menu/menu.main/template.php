<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<?function getLink($link) {
	global $APPLICATION;
	if($APPLICATION->GetCurPage() !== SITE_DIR && $link == SITE_DIR):
		$link .= "#brands";
	endif;
	return $link;
}?>

<?foreach($arResult as $arItem):?>
	<?if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;?>
	<?if($arItem["SELECTED"]):?>
		<?if($arItem["LINK"] != $APPLICATION->GetCurPage(0) && $arItem["LINK"] != SITE_DIR):?>
			<a href="<?=getLink($arItem["LINK"])?>" class="active"><?=$arItem["TEXT"]?></a>
		<?else:?>
			<span><?=$arItem["TEXT"]?></span>
		<?endif;?>
	<?else:?>
		<a href="<?=getLink($arItem["LINK"])?>"><?=$arItem["TEXT"]?></a>
	<?endif?>
<?endforeach?>

<?endif?>
