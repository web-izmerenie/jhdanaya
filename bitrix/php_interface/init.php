<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// debug
function p($arg) {
	echo '<pre>';
	print_r($arg);
	echo '</pre>';
}

function textToParagraphs($text) {
	$text = trim($text);
	$text = htmlspecialcharsex($text);
	$text = preg_replace("/\r\n/", "\n", $text);
	$text = preg_replace("/\r/", "\n", $text);
	$text = preg_replace("/\n[\n]+/", "\n\n", $text);
	$text = str_replace("\n\n", "</p><p>", $text);
	$text = str_replace("\n", "<br/>", $text);
	if (!empty($text)) $text = '<p>'.$text.'</p>';
	return $text;
}

function textOrHtmlValue($val) {
	if (strtolower($val["TYPE"]) == "text") {
		$val = textToParagraphs($val["TEXT"]);
	} elseif (strtolower($val["TYPE"]) == "html") {
		$val = trim($val["TEXT"]);
	}
	return $val;
}
