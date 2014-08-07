<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("События");
?>

<section class="events_list">
	<div class="event_item" itemscope itemtype="http://schema.org/NewsArticle">
		<h3 itemprop="headline"><a href="#"><span>Ювелирный дом «Даная» отмечал своё тринадцатилетие</span></a></h3>
		<h4 itemprop="dateline">7 апреля</h4>
		<div class="preview_text" itemprop="description">
			<p>Свой 13-ый день рождения Ювелирный Дом &laquo;Даная&raquo; отметил в&nbsp;близком кругу любимых покупателей. В&nbsp;эти дни в&nbsp;салонах, украшенных прекрасной авторской флористикой, царила праздничная атмосфера...</p>
		</div>
		<!--
			для списка новостей - максимум 4-ре превью,
			ресайз кропом 233х233,
			эквивалентны превью в детальной странице (чёрно-белыми они делаются
			через JS)
		-->
		<ul class="preview_photos">
			<li><img alt="" src="/upload/markup_tmp/events/01.png" itemprop="image" /></li>
			<li><img alt="" src="/upload/markup_tmp/events/02.png" itemprop="image" /></li>
			<li><img alt="" src="/upload/markup_tmp/events/03.png" itemprop="image" /></li>
			<li><img alt="" src="/upload/markup_tmp/events/04.png" itemprop="image" /></li>
		</ul>
	</div>
	<div class="event_item" itemscope itemtype="http://schema.org/NewsArticle">
		<h3 itemprop="headline"><span>Открылся новый магазин</span></h3>
		<h4 itemprop="dateline">21 марта</h4>
		<div class="preview_text" itemprop="description">
			<p>Свой 13-ый день рождения Ювелирный Дом &laquo;Даная&raquo; отметил в&nbsp;близком кругу любимых покупателей. В&nbsp;эти дни в&nbsp;салонах, украшенных прекрасной авторской флористикой, царила праздничная атмосфера. Гостей ожидал лёгкий и&nbsp;сладкий фуршет от&nbsp;&laquo;Sapore Italiano&raquo;, а&nbsp;специальная лотерея и&nbsp;скидки оказались приятным сюрпризом для постоянных посетителей.</p>
		</div>
	</div>
	<div class="event_item" itemscope itemtype="http://schema.org/NewsArticle">
		<h3 itemprop="headline"><a><span>Ювелирный дом «Даная» отмечал своё тринадцатилетие sd sad sda s dasda asdaas</span></a></h3>
		<h4 itemprop="dateline">8 марта</h4>
		<div class="preview_text" itemprop="description">
			<p>Свой 13-ый день рождения Ювелирный Дом &laquo;Даная&raquo; отметил в&nbsp;близком кругу любимых покупателей. В&nbsp;эти дни в&nbsp;салонах, украшенных прекрасной авторской флористикой, царила праздничная атмосфера. Гостей ожидал лёгкий и&nbsp;сладкий фуршет от&nbsp;&laquo;Sapore Italiano&raquo;, а&nbsp;специальная лотерея и&nbsp;скидки оказались приятным сюрпризом для постоянных посетителей.</p>
		</div>
	</div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
