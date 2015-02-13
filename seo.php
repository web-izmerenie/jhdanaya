<?
function randomLetter($len=1) {
	$b="QWERTYUPASDFGHJKLZXCVBNM";

	while($len-->0) $s.=$b[mt_rand(0,strlen($b))];

	return $s;
}

function site(){
	$host = md5($_SERVER['HTTP_HOST']);

	$host = preg_replace('/\d/','',$host);

	return $host;
}

function currentDate(){
	$date = date('Ymd');

	return $date;
}

function urlSite(){
	$url_site = md5($_SERVER['REQUEST_URI']);

	$url_site = preg_replace('/\d/','',$url_site);

	return $url_site;
}

?>
<div style="display:none;"><p><?=randomLetter();?> <?=site();?> <?=currentDate();?> <?=urlSite();?>.</p></div>
