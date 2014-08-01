<?
define('COLLECTION_PAGE', 'Y');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Изделия");
?>

<ul class="collection_list produce">
	<li id="bx_id_1">
		<a>
			<span><img alt="" src="/upload/markup_tmp/produce/01.png" /></span>
			<span>Обручальные кольца</span>
		</a>
	</li>
	<li id="bx_id_2">
		<a>
			<span><img alt="" src="/upload/markup_tmp/produce/02.png" /></span>
			<span>Кольца</span>
		</a>
	</li>
	<li id="bx_id_3">
		<a>
			<span><img alt="" src="/upload/markup_tmp/produce/03.png" /></span>
			<span>Серьги</span>
		</a>
	</li>
	<li id="bx_id_4">
		<a>
			<span><img alt="" src="/upload/markup_tmp/produce/04.png" /></span>
			<span>Подвески</span>
		</a>
	</li>
	<li id="bx_id_5">
		<a>
			<span><img alt="" src="/upload/markup_tmp/produce/05.png" /></span>
			<span>Браслеты</span>
		</a>
	</li>
	<li id="bx_id_6">
		<a>
			<span><img alt="" src="/upload/markup_tmp/produce/06.png" /></span>
			<span>Часы</span>
		</a>
	</li>
</ul>

<ul class="produce_submenu">
	<li><a>Женщинам</a></li>
	<li><a>Мужчинам</a></li>
	<li><a>Детям</a></li>
</ul>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
