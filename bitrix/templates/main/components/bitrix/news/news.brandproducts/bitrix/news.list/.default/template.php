<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

$show = $_GET["show"] ? $_GET["show"] : false;
$itemCount = 6;
$onPage = count($arResult["ITEMS"]);
global $arrFilter, $currentBrendID;
?>
<ul class="collection_list brand"><?
foreach($arResult["ITEMS"] as $Item){?>
    <li id="bx_id_<?=$Item["ID"]?>"><?
        if($Item["PREVIEW_PICTURE"]){?>
            <a href="<?=$Item["DETAIL_PICTURE"]["SRC"]?>" class="preview">
                <img src="<?=$Item["PREVIEW_PICTURE"]["SRC"]?>" />
            </a><?
        }?><?
        if($Item["PREVIEW_TEXT"]){?>
            <div class="info">
                <div class="text">
                    <?=$Item["PREVIEW_TEXT"]?>
                </div><?
                if($Item["PREVIEW_PICTURE"]){?>  
                    <img class="picture" alt="" src="<?=$Item["PREVIEW_PICTURE"]["SRC"]?>" /><?
                }?> 
            </div><?
        }?>    
    </li><?
}?>
</ul><?

$arFilter = array(
    "ACTIVE" => "Y",
    "IBLOCK_ID" => $arResult["ID"],
    
);
if($arResult["SECTION"]["PATH"][0]["ID"]){
    $arFilter["SECTION_ID"] = $arResult["SECTION"]["PATH"][0]["ID"];
}
if($arrFilter["PROPERTY_FOR"]){
    $arFilter["PROPERTY_FOR"] = $arrFilter["PROPERTY_FOR"];
}

$total = CIBlockElement::GetList(
    array(),
    $arFilter,
    false,
    array(),
    array()
);

$totalcount = $total->SelectedRowsCount();
if(count($arResult["ITEMS"]) && $onPage < $totalcount){?>
    <a class="load_more" title="<?=GetMessage("SHOW_MORE")?>" data-next-page="2" data-count="<?=$itemCount?>" data-iblock-section="<?=$arResult["SECTION"]["PATH"][0]["ID"]?>" data-brand="<?=$currentBrendID?>" data-for="<?=$arrFilter["PROPERTY_FOR"]?>" data-iblock="catalog"><span><?=GetMessage("SHOW_MORE")?></span></a><?
}

?>
