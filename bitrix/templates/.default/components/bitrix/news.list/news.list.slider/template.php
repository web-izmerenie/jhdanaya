<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?foreach($arResult["ITEMS"] as $arItem):?>
    <img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" />
<?endforeach;?>
