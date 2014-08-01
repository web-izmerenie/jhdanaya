<?
define('COLLECTION_PAGE', 'Y');
define('COLLECTION_BRAND_PAGE', 'Y');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Коллекция");
?>

<ul class="collection_list brand">
	<li id="bx_id_1">
		<a class="preview">
			<img alt="" src="/upload/markup_tmp/collection/01.png" />
		</a>
		<div class="info">
			<div class="text">
				<p>Арт. ТТ3932</p>
				<p>
					Белое золото: Проба: 585.<br/>
					Обработка: Алмазная грань<br/>
					Желтое золото: Проба: 585.<br/>
					Обработка: Алмазная грань<br/>
					Вес: 3,02 г.<br/>
					Размер: 15,5 мм.
				</p>
				<p>
					ТРЦ «Золотой Вавилон»<br/>
					Тел.: (863) 204-07-40
				</p>
			</div>
			<img class="picture" alt="" src="/upload/markup_tmp/collection/01.png" />
		</div>
	</li>
	<li id="bx_id_2">
		<a class="preview">
			<img alt="" src="/upload/markup_tmp/collection/02.png" />
		</a>
		<div class="info">
			<div class="text">
				<p>Арт. ТТ3932</p>
				<p>
					Белое золото: Проба: 585.<br/>
					Обработка: Алмазная грань<br/>
					Желтое золото: Проба: 585.<br/>
					Обработка: Алмазная грань<br/>
					Вес: 3,02 г.<br/>
					Размер: 15,5 мм.
				</p>
				<p>
					ТРЦ «Золотой Вавилон»<br/>
					Тел.: (863) 204-07-40
				</p>
			</div>
			<img class="picture" alt="" src="/upload/markup_tmp/collection/02.png" />
		</div>
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
		<div class="info">
			<div class="text">
				<p>Арт. ТТ3932</p>
				<p>
					Белое золото: Проба: 585.<br/>
					Обработка: Алмазная грань<br/>
					Желтое золото: Проба: 585.<br/>
					Обработка: Алмазная грань<br/>
					Вес: 3,02 г.<br/>
					Размер: 15,5 мм.
				</p>
				<p>
					ТРЦ «Золотой Вавилон»<br/>
					Тел.: (863) 204-07-40
				</p>
			</div>
			<img class="picture" alt="" src="/upload/markup_tmp/collection/05.png" />
		</div>
	</li>
</ul>

<!--
	1. см. путь до обработчика в values.js под ключом: dynamicItemsLoadURL
	2. data-next-page - это следующая страница, которая будет подгружена, будет передано под ключом "page"
	3. data-count - этот параметр передаётся под ключом "count", сколько новостей подгрузится при клике (можно просто стереть)
-->
<a class="load_more" title="Загрузить еще" data-next-page="2" data-count="2"><span>Загрузить еще</span></a>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
