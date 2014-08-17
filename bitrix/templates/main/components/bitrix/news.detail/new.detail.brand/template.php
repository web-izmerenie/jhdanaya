<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="about_brand">
	<section class="based">
		<p>
			<?=GetMessage("FOUNDATION_YEAR")?> — <?=$arResult["DISPLAY_PROPERTIES"]["YEAR"]["VALUE"]?><br/>
			<?=GetMessage("FOUNDATION_PLACE")?> — <?=$arResult["DISPLAY_PROPERTIES"]["PLACE"]["VALUE"]?>
		</p>
	</section><?
	if($arResult["DISPLAY_PROPERTIES"]["Q_PICTURE"]["VALUE"] || $arResult["DISPLAY_PROPERTIES"]["Q_TEXT"]["VALUE"]){?>
	<blockquote><?
		if($arResult["DISPLAY_PROPERTIES"]["Q_PICTURE"]["VALUE"]){
			$ar_q_picture = CFile::GetFileArray($arResult["DISPLAY_PROPERTIES"]["Q_PICTURE"]["VALUE"]);
			if($ar_q_picture["WIDTH"] > 118){
				$q_picture = CFile::ResizeImageGet($arResult["DISPLAY_PROPERTIES"]["Q_PICTURE"]["VALUE"], array("width" => "118", "height" => "118"), BX_RESIZE_IMAGE_PROPORTIONAL);
			}else{
				$q_picture["src"] = CFile::GetPath($arResult["DISPLAY_PROPERTIES"]["Q_PICTURE"]["VALUE"]);
			}?>
			<img alt="<?=$arResult["DISPLAY_PROPERTIES"]["Q_AUTHOR"]["VALUE"]?>" src="<?=$q_picture["src"]?>" /><?
		}?>
		<?=$arResult["DISPLAY_PROPERTIES"]["Q_TEXT"]["~VALUE"]["TEXT"]?>
		<p class="signature"><?=$arResult["DISPLAY_PROPERTIES"]["Q_AUTHOR"]["VALUE"]?></p>
	</blockquote><?
	}?>

	<section class="accent_info_block"><?
		if($arResult["DETAIL_PICTURE"]){?>
			<div class="col_l picture" style="background-image:url('<?=$arResult["DETAIL_PICTURE"]["SRC"]?>');"></div><?
		}?><?
		if($arResult["DETAIL_TEXT"]){?>
		<div class="col_r info">
			<?=$arResult["DETAIL_TEXT"]?>
		</div><?
		}?>
	</section>
	<section class="info_right_pic"><?
		if($arResult["DISPLAY_PROPERTIES"]["BL1_PICTURE"]["VALUE"]){
			$ar_bl1_picture = CFile::GetFileArray($arResult["DISPLAY_PROPERTIES"]["BL1_PICTURE"]["VALUE"]);
			if($ar_bl1_picture["WIDTH"] > 304){
				$bl1_picture = CFile::ResizeImageGet($arResult["DISPLAY_PROPERTIES"]["BL1_PICTURE"]["VALUE"], array("width" => "304", "height" => "304"), BX_RESIZE_IMAGE_PROPORTIONAL);
			}else{
				$bl1_picture["src"] = CFile::GetPath($arResult["DISPLAY_PROPERTIES"]["BL1_PICTURE"]["VALUE"]);
			}?>
			<img alt="<?=$arResult["DISPLAY_PROPERTIES"]["BL1_PICTURE"]["DESCRIPTION"]?>" src="<?=$bl1_picture["src"]?>" /><?
		}?><?
		if($arResult["DISPLAY_PROPERTIES"]["BL1_TEXT"]["~VALUE"]["TEXT"]){?>
		<?=$arResult["DISPLAY_PROPERTIES"]["BL1_TEXT"]["~VALUE"]["TEXT"]?><?
		}?>
	</section>
	<div class="figure_split"></div>

	<section class="two_column_info"><?
		if($arResult["DISPLAY_PROPERTIES"]["BL2_CODE"]["VALUE"]){?>
			<div class="col_l">
				<div class="youtube_video" data-youtube-id="<?=$arResult["DISPLAY_PROPERTIES"]["BL2_CODE"]["VALUE"]?>"></div>
			</div><?
		}?><?
		if($arResult["DISPLAY_PROPERTIES"]["BL2_TEXT"]["~VALUE"]["TEXT"]){?>
			<div class="col_r">
				<?=$arResult["DISPLAY_PROPERTIES"]["BL2_TEXT"]["~VALUE"]["TEXT"]?>
			</div><?
		}?>
	</section><?
	if($arResult["DISPLAY_PROPERTIES"]["PROMO_SLOGAN"]["~VALUE"]["TEXT"]){?>
		<section class="notation">
			<?=$arResult["DISPLAY_PROPERTIES"]["PROMO_SLOGAN"]["~VALUE"]["TEXT"]?>
		</section><?
	}?><?
	if(count($arResult["DISPLAY_PROPERTIES"]["PROMO_GALLERY"]["VALUE"])){?>
		<!-- превьюшки должны ограничиваться по ширине в 140, по высоте в 200 -->
		<ul class="production_list"><?
			foreach($arResult["DISPLAY_PROPERTIES"]["PROMO_GALLERY"]["VALUE"] as $id){
				$ar_id = CFile::GetFileArray($id);
				$bx_resize_image = $ar_id["WIDTH"] > $ar_id["HEIGHT"] ? "BX_RESIZE_IMAGE_PROPORTIONAL" : "BX_RESIZE_IMAGE_PROPORTIONAL_ALT";
				$thumd = CFile::ResizeImageGet($id, array("width" => "140", "height" => "200"), $bx_resize_image);?>
				<li><img alt="" src="<?=$thumd["src"]?>" /></li><?
			}?>
		</ul><?
	}?>
	<section class="back_link"><?
		$ar_small_picture = CFile::GetFileArray($arResult["DISPLAY_PROPERTIES"]["SMALL_PICTURE"]["VALUE"]);
		if($ar_small_picture["WIDTH"] > 57){
			$small_picture = CFile::ResizeImageGet($arResult["DISPLAY_PROPERTIES"]["SMALL_PICTURE"]["VALUE"], array("width" => "57", "height" => "57"), BX_RESIZE_IMAGE_PROPORTIONAL);
		}else{
			$small_picture["src"] = CFile::GetPath($arResult["DISPLAY_PROPERTIES"]["SMALL_PICTURE"]["VALUE"]);
		}?>
		<a href="/brand/<?=$arResult["CODE"]?>/?show=all">
			<img alt="" src="<?=$small_picture["src"]?>" />
			<span><?=GetMessage("COLLECTION_BRAND")?></span>
		</a>
	</section>
</div>
