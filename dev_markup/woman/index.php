<?
define('COLLECTION_PAGE', 'Y');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Женщинам");
?>

<ul class="collection_list rings">
	<li id="bx_id_1">
		<a class="preview">
			<img alt="" src="/upload/markup_tmp/rings/01.png" />
		</a>
		<div class="info">
			<div class="text">
				Белое золото<br/>
				Желтое золото<br/>
				Вес: 3,02 г.<br/>
				Размер: 15,5 мм.
			</div>
		</div>
	</li>
	<li id="bx_id_2">
		<a class="preview">
			<img alt="" src="/upload/markup_tmp/rings/02.png" />
		</a>
		<div class="info">
			<div class="text">
				Белое золото<br/>
				Желтое золото<br/>
				Вес: 3,02 г.<br/>
				Размер: 15,5 мм.
			</div>
		</div>
	</li>
	<li id="bx_id_3">
		<span class="preview">
			<img alt="" src="/upload/markup_tmp/rings/03.png" />
		</span>
	</li>
	<li id="bx_id_4">
		<span class="preview">
			<img alt="" src="/upload/markup_tmp/rings/04.png" />
		</span>
	</li>
	<li id="bx_id_5">
		<a class="preview">
			<img alt="" src="/upload/markup_tmp/rings/05.png" />
		</a>
		<div class="info">
			<div class="text">
				Белое золото<br/>
				Желтое золото<br/>
				Вес: 3,02 г.<br/>
				Размер: 15,5 мм.
			</div>
		</div>
	</li>
	<li id="bx_id_6">
		<span class="preview">
			<img alt="" src="/upload/markup_tmp/rings/06.png" />
		</span>
	</li>
	<li id="bx_id_7">
		<a class="preview">
			<img alt="" src="/upload/markup_tmp/rings/01.png" />
		</a>
		<div class="info">
			<div class="text">
				Белое золото<br/>
				Желтое золото<br/>
				Вес: 3,02 г.<br/>
				Размер: 15,5 мм.
			</div>
		</div>
	</li>
	<li id="bx_id_8">
		<a class="preview">
			<img alt="" src="/upload/markup_tmp/rings/02.png" />
		</a>
		<div class="info">
			<div class="text">
				Белое золото<br/>
				Желтое золото<br/>
				Вес: 3,02 г.<br/>
				Размер: 15,5 мм.
			</div>
		</div>
	</li>
	<li id="bx_id_9">
		<span class="preview">
			<img alt="" src="/upload/markup_tmp/rings/03.png" />
		</span>
	</li>
</ul>

<!--
	1. см. путь до обработчика в values.js под ключом: dynamicItemsLoadURL
	2. data-next-page - это следующая страница, которая будет подгружена, будет передано под ключом "page"
	3. data-count - этот параметр передаётся под ключом "count", сколько новостей подгрузится при клике (можно просто стереть)
-->
<a class="load_more" title="Загрузить еще" data-next-page="2" data-count="2"><span>Загрузить еще</span></a>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
