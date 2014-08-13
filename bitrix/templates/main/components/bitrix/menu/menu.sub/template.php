<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<nav class="nav_block">
<?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
		continue;
?>
	<?if($arItem["SELECTED"]):?>
		<?if($arItem["LINK"] != $APPLICATION->GetCurPage(0)):?>
			<a href="<?=$arItem["LINK"]?>" class="active"><span><?=$arItem["TEXT"]?></span></a>
		<?else:?>
			<span><?=$arItem["TEXT"]?></span>
		<?endif;?>
	<?else:?>
		<a href="<?=$arItem["LINK"]?>"><span><?=$arItem["TEXT"]?></span></a>
	<?endif?>
<?endforeach?>
</nav>
<?endif?>
