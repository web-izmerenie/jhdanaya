<?
global $APPLICATION, $tplPathBrand, $currentBrendID;
$dir = $APPLICATION->GetCurDir();
$page = $APPLICATION->GetCurPage();

CModule::IncludeModule("iblock");
$aMenuLinks = array();
$section = CIBlockSection::GetList(
    array(
        "SORT" => "asc"
    ),
    array(
        "IBLOCK_CODE" => "catalog",
        "PROPERTY" => array(
            "BRAND" => $currentBrendID
        )
    ),
    true
);
if($section->SelectedRowsCount()){
    while($arSection = $section->GetNExt()){
        if($arSection["ELEMENT_CNT"]){
            $aMenuLinks[] = Array(
                $arSection["NAME"], 
                "/brand/" . $tplPathBrand[2] . "/" . $arSection["CODE"] . "/",
                array(), 
                array(), 
                "" 
            );
        }
    }
    $aMenuLinks[] = Array(
        GetMessage("ALL"), 
        $arDir[0] . $tplPathBrand[2]. "/?show=all", 
        array(), 
        array(), 
        "" 
    );
}


?>