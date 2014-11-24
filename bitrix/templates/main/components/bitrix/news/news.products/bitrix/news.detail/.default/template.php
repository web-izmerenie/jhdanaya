<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="collection_detail">
	<a href="<?=$arResult['SECTION']['PATH'][0]['SECTION_PAGE_URL']
		?>" class="back_to_list"><?=GetMessage('BACK_TO_LIST')?></a>

	<div class="detail_content">
		<div class="col_description">
			<p><?=$arResult['ART']?></p>
			<?=$arResult["PREVIEW_TEXT"]?>
		</div>

		<div class="picture">
			<div class="img_wrap">
				<img alt="" src="<?=$arResult['PICTURE']['src']?>" />
			</div>
			<a href="<?=$arResult['PICTURE']['src']
				?>" class="show_big" title="<?=GetMessage('SHOW_BIG_PICTURE')
				?>"><?=GetMessage('SHOW_BIG_PICTURE')?></a>
		</div>
	</div>

	<div class="el_description">
	</div>
</div>
