<?
define('COLLECTION_PAGE', 'Y');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Коллекция");
?>

<ul class="collection_list">
	<li>
		<span class="preview">
			<img alt="" src="/upload/markup_tmp/collection/01.png" />
		</span>
	</li>
	<li>
		<span class="preview">
			<img alt="" src="/upload/markup_tmp/collection/02.png" />
		</span>
	</li>
	<li>
		<span class="preview">
			<img alt="" src="/upload/markup_tmp/collection/03.png" />
		</span>
	</li>
	<li>
		<span class="preview">
			<img alt="" src="/upload/markup_tmp/collection/04.png" />
		</span>
	</li>
	<li>
		<span class="preview">
			<img alt="" src="/upload/markup_tmp/collection/05.png" />
		</span>
	</li>
</ul>

<a class="load_more" title="Загрузить ещё"><span>Загрузить ещё</span></a>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
