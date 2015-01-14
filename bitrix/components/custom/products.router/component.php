<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// init requirements {{{1

$requiredModules = array('iblock', 'highloadblock');

foreach ($requiredModules as $requiredModule) {
	if (!CModule::IncludeModule($requiredModule)) {
		ShowError(GetMessage(
			"F_NO_MODULE", array('#MODULE_NAME#' => $requiredModule)));
		return 0;
	}
}

// init requirements }}}1

require $_SERVER['DOCUMENT_ROOT'].'/inc/get_for_list.php';

$pathname = $arParams['ROUTE_PATH'];
$pathname = explode('?', $pathname);
$pathname = $pathname[0];

$arIBlock = GetIBlock($arParams['IBLOCK_ID'], $arParams['IBLOCK_TYPE']);

// no need to route
if (strpos($pathname, $arIBlock['LIST_PAGE_URL']) !== 0) {
	$this->SetResultCacheKeys();
	$this->IncludeComponentTemplate($template);
	return;
}

$routePathname = substr($pathname, strlen($arIBlock['LIST_PAGE_URL']));

$arResult['IBLOCK_TYPE'] = $arParams['IBLOCK_TYPE'];
$arResult['IBLOCK_ID'] = $arParams['IBLOCK_ID'];

$arResult['PATHNAME'] = $pathname;
$arResult['ROUTE_PATHNAME'] = $routePathname;

$arResult['FOR'] = null;

$forList = $getForList($arParams['IBLOCK_TYPE'], $arParams['IBLOCK_ID']);
$arResult['FOR_LIST'] = $forList;

$split = explode('/', $routePathname);

if (count($split) > 1) {
	foreach ($forList as $forItem) {
		if ($forItem['CODE'] == $split[0]) {
			$arResult['FOR'] = $forItem['CODE'];
			array_shift($split);
			$APPLICATION->SetTitle($forItem['NAME']);
			break;
		}
	}
}

if (count($split) === 1 && empty($split[0])) {
	$template = 'sections';
} else if (count($split) === 2 && !empty($split[0]) && empty($split[1])) {
	if ($split[0] !== 'all') {
		$arResult['SECTION_CODE'] = $split[0];
	}
	$template = 'elements';
} else if (count($split) === 2 && !empty($split[0]) && !empty($split[1])) {
	preg_match('/^(.*?).html$/', $split[1], $matches);
	if ($matches) {
		$arResult['SECTION_CODE'] = $split[0];
		$arResult['ELEMENT_CODE'] = $matches[1];
		$template = 'detail';
	} else {
		define('ERROR_404', 'Y');
		CHTTP::SetStatus("404 Not Found");
		ShowError(GetMessage('PAGE_NOT_FOUND'));
		return;
	}
} else {
	define('ERROR_404', 'Y');
	CHTTP::SetStatus("404 Not Found");
	ShowError(GetMessage('PAGE_NOT_FOUND'));
	return;
}

$this->IncludeComponentTemplate($template);
