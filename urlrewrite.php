<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/brand/((?!about).+|about.+)/(.*)/#",
		"RULE" => "BRAND=\$1&SECTION=\$2",
		"ID" => "bitrix:news",
		"PATH" => "/brand/index.php",
	),
	array(
		"CONDITION" => "#^/robots.txt(\\\\?|\\\$)#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/robots.php",
	),
	array(
		"CONDITION" => "#^/brand/about/(.*)/#",
		"RULE" => "BRAND=\$1",
		"ID" => "bitrix:news.detail",
		"PATH" => "/brand/about/index.php",
	),
	array(
		"CONDITION" => "#^/events/(.*)/#",
		"RULE" => "CODE=\$1",
		"ID" => "bitrix:news.detail",
		"PATH" => "/events/detail.php",
	),
	array(
		"CONDITION" => "#^/brand/(.*)/#",
		"RULE" => "BRAND=\$1",
		"ID" => "bitrix:news",
		"PATH" => "/brand/index.php",
	),
	array(
		"CONDITION" => "#^/products/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/products/index.php",
	),
	array(
		"CONDITION" => "#^/brand/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/bitrix/templates/main/header.php",
	),
);

?>