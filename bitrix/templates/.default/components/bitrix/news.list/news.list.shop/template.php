<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul class="shops_list">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
    <li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <div class="top_line"><?
            if($arItem["DISPLAY_PROPERTIES"]["LOGO"]["VALUE"]){
                $logo = CFile::ResizeImageGet(
                    $arItem["DISPLAY_PROPERTIES"]["LOGO"]["VALUE"],
                    array("widht" => "193", "height" => "50"),
                    BX_RESIZE_IMAGE_PROPORTIONAL
                );?>
                <img class="brand" alt="" src="<?=$logo["src"]?>" /><?
            }?>
            <address><?=$arItem["DISPLAY_PROPERTIES"]["ADDRESS"]["VALUE"]?></address><?
            $phone = str_replace("-", "", $arItem["DISPLAY_PROPERTIES"]["PHONE"]["VALUE"]);
            $phone = str_replace(" ", "", $phone);
            $phone = str_replace(")", "", $phone);
            $phone = str_replace("(", "", $phone);
            $phone = substr_replace($phone, "+7", 0, 1);
            ?>
            <div class="phone"><?=GetMessage("SHORT_PHONE")?> <a href="tel:<?=$phone?>"><?=$arItem["DISPLAY_PROPERTIES"]["PHONE"]["VALUE"]?></a></div>
        </div>
        <div class="photos">
            <div class="left_col"><img alt="" src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" /></div>
            <div class="right_col"><?
            if($arItem["DISPLAY_PROPERTIES"]["SIDE_PIC"]["VALUE"]){
                foreach($arItem["DISPLAY_PROPERTIES"]["SIDE_PIC"]["VALUE"] as $id){
                    $side = CFile::ResizeImageGet($id, array("width" => "313", "height" => "210"), BX_RESIZE_IMAGE_PROPORTIONAL);?>
                    <img alt="" src="<?=$side["src"]?>" /><?
                }
            }?>
            </div>
        </div>
    </li>
<?endforeach;?>
</ul>
