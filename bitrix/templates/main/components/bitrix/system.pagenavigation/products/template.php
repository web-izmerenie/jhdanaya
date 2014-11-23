<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<ul class="pages_list">
	<?for($i=$arResult["nStartPage"]; $i<=$arResult["nEndPage"]; $i++):?>
		<li>
			<?if($i == $arResult["NavPageNomer"]):?>
				<b><?=$i?></b>
			<?elseif($i == 1):?>
				<a href="<?=$arResult["sUrlPath"]?>"><?=$i?></a>
			<?else:?>
				<a href="<?=$arResult["sUrlPath"]
					?>?PAGEN_<?=$arResult["NavNum"]
					?>=<?=$i?>"><?=$i?></a>
			<?endif?>
		</li>
	<?endfor?>
</ul>
