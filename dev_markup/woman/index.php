<?
define('COLLECTION_PAGE', 'Y');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Женщинам");
?>

<ul class="collection_list rings">
	<li id="bx_id_1">
		<a class="preview">
			<img alt="" src="/upload/markup_tmp/collection/01.png" />
		</a>
	</li>
	<li id="bx_id_2">
		<a class="preview">
			<img alt="" src="/upload/markup_tmp/collection/02.png" />
		</a>
	</li>
	<li id="bx_id_3">
		<span class="preview">
			<img alt="" src="/upload/markup_tmp/collection/03.png" />
		</span>
	</li>
	<li id="bx_id_4">
		<span class="preview">
			<img alt="" src="/upload/markup_tmp/collection/04.png" />
		</span>
	</li>
	<li id="bx_id_5">
		<a class="preview">
			<img alt="" src="/upload/markup_tmp/collection/05.png" />
		</a>
	</li>
</ul>

<!--
	1. см. путь до обработчика в values.js под ключом: dynamicItemsLoadURL
	2. data-next-page - это следующая страница, которая будет подгружена, будет передано под ключом "page"
	3. data-count - этот параметр передаётся под ключом "count", сколько новостей подгрузится при клике (можно просто стереть)
-->
<a class="load_more" title="Загрузить еще" data-next-page="2" data-count="2"><span>Загрузить еще</span></a>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
