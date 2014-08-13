<?
global $APPLICATION;
$dir = $APPLICATION->GetCurDir();

$arDir = explode("/", $dir);
foreach($arDir as $k => $v){
    if($v === "") unset($arDir[$k]);
}
if(count($arDir) > 1){
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

/* $aMenuLinks = Array(
	Array(
		"Бренды", 
		"/", 
		Array(), 
		Array(), 
		"" 
	), */
?>