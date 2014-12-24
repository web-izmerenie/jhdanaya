<?header('Content-Type: text/plain; charset=utf-8')?>
<?if(preg_match('/dev-(.+)\.(.+)\.(.+)/i', $_SERVER['HTTP_HOST'])):?>
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
