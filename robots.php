<?header('Content-Type: text/plain; charset=utf-8')?>
<?require $_SERVER['DOCUMENT_ROOT'].'/allowed_domains.php';?>
<?if(!in_array($_SERVER['HTTP_HOST'], $ALLOWED_DOMAINS)):?>
User-Agent: *
Disallow: /
<?else:?>
User-agent: *
Allow: /bitrix/templates/
Disallow: /bitrix/
Disallow: /search/
Disallow: /web-front-end-deploy/
Disallow: /web-front-end-gulp-template/
Disallow: /_deploy/
Disallow: /.git/
Disallow: /node_modules/
Disallow: /ie_old/
Disallow: /ajax/
Host: jhdanaya.ru
<?endif?>
