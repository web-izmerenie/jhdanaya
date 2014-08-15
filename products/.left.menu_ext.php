<?
global $APPLICATION;
$dir = $APPLICATION->GetCurDir();
$page = $APPLICATION->GetCurPage();

$arDir = explode("/", $dir);
foreach($arDir as $k => $v){
    if($v === "") unset($arDir[$k]);
}

if((count($arDir) > 1 || $_GET["show"] === "all") && ($page != "/products/women/" && $page != "/products/men/" && $page != "/products/cildren/")){
    CModule::IncludeModule("iblock");
    $aMenuLinks = array();
    $section = CIBlockSection::GetList(
        array(
            "SORT" => "asc"
        ),
        array(
            "IBLOCK_CODE" => "catalog"
        )
    );
    if($section->SelectedRowsCount()){
        while($arSection = $section->GetNExt()){
            if(stripos($APPLICATION->GetCurDir(), "/women/"))
                $arSection["SECTION_PAGE_URL"] = str_replace("products", "products/women", $arSection["SECTION_PAGE_URL"]);
            if(stripos("/men/", $APPLICATION->GetCurDir()))
                $arSection["SECTION_PAGE_URL"] = str_replace("products", "products/men", $arSection["SECTION_PAGE_URL"]);
            if(stripos("/children/", $APPLICATION->GetCurDir()))
                $arSection["SECTION_PAGE_URL"] = str_replace("products", "products/children", $arSection["SECTION_PAGE_URL"]);
                
            $aMenuLinks[] = Array(
                $arSection["NAME"], 
                $arSection["SECTION_PAGE_URL"], 
                array(), 
                array(), 
                "" 
            );
        }
        $aMenuLinks[] = Array(
            GetMessage("ALL"), 
            $arDir[0] . "?show=all", 
            array(), 
            array(), 
            "" 
        );
    }
}

?>