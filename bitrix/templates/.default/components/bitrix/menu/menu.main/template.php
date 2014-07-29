<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<?if($arItem["SELECTED"]):?>
        <?if($arItem["LINK"] != $APPLICATION->GetCurPage()):?>
            <a href="<?=$arItem["LINK"]?>" class="selected"><?=$arItem["TEXT"]?></a>
        <?else:?>
            <span><?=$arItem["TEXT"]?></span>
        <?endif;?>
	<?else:?>
		<a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
	<?endif?>
	
<?endforeach?>

<?endif?>