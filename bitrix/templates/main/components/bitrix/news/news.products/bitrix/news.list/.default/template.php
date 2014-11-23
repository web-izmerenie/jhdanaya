<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(is_array($arResult["SECTION"])){?>

	<ul class="collection_list rings products">
	<?foreach($arResult["ITEMS"] as $Item){?>
		<li id="bx_id_<?=$Item["ID"]?>">
			<?
			$sectionCode = explode('/', $GLOBALS['APPLICATION']->GetCurDir(0));
			$sectionCode = $sectionCode[2];
			$sectionRes = CIBlockSection::GetList(
				array(),
				array(
					"IBLOCK_ID" => $arResult["ID"],
					"CODE" => $sectionCode,
				)
			);
			$arSection = $sectionRes->GetNext();
			?>
			<?
			$linkTitle =
				GetMessage("ART.").'&nbsp;'
				.$Item["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"];
			?>
			<a href="/products/<?=$sectionCode?>/<?=$Item["ID"]
				?>.html" title="<?=$linkTitle
				?>"><?=$arSection["NAME"].'. '.$linkTitle?></a>
			<div class="preview">
				<img alt="<?=$Item["PREVIEW_PICTURE"]["DESCRIPTION"]
					?>" src="<?=$Item["PREVIEW_PICTURE"]["SRC"]?>" />
			</div>
		</li>
	<?}?>
	</ul>

<?}else{?>

	<?
	global $arrFilter;
	$sFilter = array(
		"IBLOCK_ID" => $arResult["ID"]
	);
	if($arrFilter["PROPERTY_FOR"]){
		$sFilter["PROPERTY"] = array(
			"FOR" => $arrFilter["PROPERTY_FOR"],
			"BRAND" => $arrFilter["PROPERTY_BRAND"]
		);
	}
	$section = CIBlockSection::GetList(
		array(
			"SORT" => "asc"
		),
		$sFilter
	);
	?>
	<ul class="collection_list produce">
		<?if($section->SelectedRowsCount()){?>
			<?while($arSection = $section->GetNext()){?>
				<?
				if(stripos($APPLICATION->GetCurDir(), "/women/"))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/women", $arSection["SECTION_PAGE_URL"]);
				if(stripos("/men/", $APPLICATION->GetCurDir()))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/men", $arSection["SECTION_PAGE_URL"]);
				if(stripos("/children/", $APPLICATION->GetCurDir()))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/children", $arSection["SECTION_PAGE_URL"]);
				?>
				<li id="bx_id_<?=$arSection["ID"]?>">
					<?$arSectionPic = CFile::GetFileArray($arSection["PICTURE"]);?>
					<a href="<?=$arSection["SECTION_PAGE_URL"] . $urlsuffix?>" title="<?=$arSection["NAME"]?>">
						<span><img alt="<?=$arSectionPic["DESCRIPTION"]
							?>" src="<?=$arSectionPic["SRC"]
							?>" width="<?=$arSectionPic["WIDTH"]
							?>" height="<?=$arSectionPic["HEIGHT"]
							?>" /></span>
						<span><?=$arSection["NAME"]?></span>
					</a>
				</li>
			<?}?>
		<?}?>
	</ul>

	<?
	$depth = trim($APPLICATION->GetCurDir(0));
	if ($depth[0] === '/') $depth = substr($depth, 1);
	if (substr($depth, -1) === '/') $depth = substr($depth, 0, -1);
	$depth = count( explode('/', $depth) );
	?>
	<?if($depth < 2){?>
		<ul class="produce_submenu">
			<?$path = $APPLICATION->GetCurDir()?>
			<li><a href="<?=$path?>women/"><?=GetMessage("FOR_WOMEN")?></a></li>
			<li><a href="<?=$path?>men/"><?=GetMessage("FOR_MEN")?></a></li>
			<li><a href="<?=$path?>children/"><?=GetMessage("FOR_CHILDREN")?></a></li>
		</ul>
	<?}?>

<?}?>
