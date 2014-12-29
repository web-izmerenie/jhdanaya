	<?if(!defined('ERROR_404')){?></main><?}?>
	<footer>
		<div class="social">
			<a
				href="http://instagram.com/jhdanaya/"
				class="instagram"
				title="Instagram"
				target="_blank">Instagram</a>
			<a
				href="https://www.facebook.com/pages/%D0%AE%D0%B2%D0%B5%D0%BB%D0%B8%D1%80%D0%BD%D1%8B%D0%B9-%D0%B4%D0%BE%D0%BC-%D0%94%D0%B0%D0%BD%D0%B0%D1%8F/607619165995029"
				class="fb"
				title="Facebook"
				target="_blank">Facebook</a>
		</div>
		<div class="developer">
			<?=GetMessage("DEVELOPER")?>
		</div>
	</footer>
	<?if(!preg_match('/dev-(.+)\.(.+)\.(.+)/i', $_SERVER['HTTP_HOST'])):?>
		<!-- Yandex.Metrika counter -->
		<script type="text/javascript">
		(function (d, w, c) {
			(w[c] = w[c] || []).push(function() {
				try {
					w.yaCounter26647785 = new Ya.Metrika({id:26647785,
							webvisor:true,
							clickmap:true,
							trackLinks:true,
							accurateTrackBounce:true});
				} catch(e) { }
			});

			var n = d.getElementsByTagName("script")[0],
				s = d.createElement("script"),
				f = function () { n.parentNode.insertBefore(s, n); };
			s.type = "text/javascript";
			s.async = true;
			s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

			if (w.opera == "[object Opera]") {
				d.addEventListener("DOMContentLoaded", f, false);
			} else { f(); }
		})(document, window, "yandex_metrika_callbacks");
		</script>
		<noscript><div><img src="//mc.yandex.ru/watch/26647785" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->
	<?endif?>
</body>
</html>
