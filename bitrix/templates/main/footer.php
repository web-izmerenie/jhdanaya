	<?if(!defined('ERROR_404')){?></main><?}?>
	<footer>
		<div class="developer">
			<?=GetMessage("DEVELOPER")?>
		</div>
	</footer>
	<?require $_SERVER['DOCUMENT_ROOT'].'/allowed_domains.php';?>
	<?if(in_array($_SERVER['HTTP_HOST'], $ALLOWED_DOMAINS)):?>
		<!-- Yandex.Metrika counter -->
		<script type="text/javascript">
		(function (d, w, c) {
			document.write('<div><img src="//mc.yandex.ru/watch/26647785" '+
				'style="position:absolute; left:-9999px;" alt="" /></div>');

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
		<!-- /Yandex.Metrika counter -->
	<?endif?>
</body>
</html>
