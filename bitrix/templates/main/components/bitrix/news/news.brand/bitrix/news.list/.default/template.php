<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul class="brands_list">
<?foreach($arResult["ITEMS"] as $arItem):?>
    <li><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></li>
<?endforeach;?>
</ul>
