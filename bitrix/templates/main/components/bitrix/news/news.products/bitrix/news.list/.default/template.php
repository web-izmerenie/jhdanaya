<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

$count = count($arResult["ITEMS"]);

if($count){

}else{
    $section = CIBlockSection::GetList(
        array(
            "SORT" => "asc"
        ),
        array(
            "IBLOCK_ID" => $arResult["ID"]
        )
        
    );
    if($section->SelectedRowsCount()){?>
        <ul class="collection_list produce"><?            
            while($arSection = $section->GetNext()){?>
                <li id="bx_id_<?=$arSection["ID"]?>"><?
                    $arFile = CFile::GetFileArray($arSection["PICTURE"]);
                    if($arFile["WIDTH"] > 296){
                        $thumb = CFile::ResizeImageGet($arSection["PICTURE"], array("width" => "296", "height" => "159"), BX_RESIZE_IMAGE_PROPORTIONAL);
                    }else{
                        $thumb["src"] = CFile::GetPath($arSection["PICTURE"]);
                    }
                    ?>
                    <a href="<?=$arSection["SECTION_PAGE_URL"]?>" title="<?=$arSection["NAME"]?>">
                        <span><img alt="<?=$arSection["NAME"]?>" src="<?=$thumb["src"]?>" /></span>
                        <span><?=$arSection["NAME"]?></span>
                    </a>
                </li><?
            }?>            
        </ul><?
    }?>
        <ul class="produce_submenu"><?
            $path = $APPLICATION->GetCurDir()?>
            <li><a href="<?=$path?>?show=women"><?=GetMessage("FOR_WOMEN")?></a></li>
            <li><a href="<?=$path?>?show=men"><?=GetMessage("FOR_MEN")?></a></li>
            <li><a href="<?=$path?>?show=children"><?=GetMessage("FOR_CHILDREN")?></a></li>
        </ul><?
}
?>
