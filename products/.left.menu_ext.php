<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// init requirements {{{1

$requiredModules = array('iblock', 'highloadblock');

foreach ($requiredModules as $requiredModule) {
	if (!CModule::IncludeModule($requiredModule)) {
		return;
	}
}

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

// init requirements }}}1

$iblock_type = 'lists';
$iblock_id = '4';

global $APPLICATION;

$aMenuLinks = array();

$getForList = function ($iblock_type, $iblock_id) {
	// find "FOR" property
	$res = CIBlockProperty::GetByID('FOR', $iblock_id);
	$arProp = $res->GetNext();
	if (!$arProp) return false;

	// get table name
	$arPropSettings = CIBlockPropertyDirectory::PrepareSettings($arProp);

	$hlblock = HL\HighloadBlockTable::getList(
		array(
			'filter' => array(
				'TABLE_NAME' => $arPropSettings['TABLE_NAME'],
			)
		)
		)->fetch();
	$entity = HL\HighloadBlockTable::compileEntity($hlblock);

	$main_query = new Entity\Query($entity);
	$main_query->setSelect(array('*'));
	$main_query->setOrder(array('UF_SORT' => 'ASC'));

	$result = $main_query->exec();
	$result = new CDBResult($result);

	$rows = array();

	while ($row = $result->Fetch()) {
		$newRow = array(
			'ID' => $row['ID'],
			'NAME' => $row['UF_NAME'],
			'SORT' => $row['UF_SORT'],
			'CODE' => $row['UF_XML_ID'],
			'COUNT' => 0,
		);

		// for counting active elements
		$elRes = CIBlockElement::GetList(
			array(),
			array(
				"ACTIVE" => "Y",
				"IBLOCK_TYPE" => $iblock_type,
				"IBLOCK_ID" => $iblock_id,
				"PROPERTY_FOR" => $newRow['CODE'],
			),
			false,
			array('nPageSize' => 5000)
			);

		while ($arEl = $elRes->GetNext()) {
			// elements must be in a section
			if (!$arEl['IBLOCK_SECTION_ID']) continue;

			// only active elements that in active sections
			$sRes = CIBlockSection::GetByID($arEl['IBLOCK_SECTION_ID']);
			$arSection = $sRes->GetNext();
			if ($arSection['ACTIVE'] != 'Y') continue;

			$newRow['COUNT']++;
		}

		$rows[] = $newRow;
	}

	return $rows;
};

// get iblock
$arIBlock = GetIBlock($iblock_id, $iblock_type);

$forList = $getForList($iblock_type, $iblock_id);
if ($forList === false) return;

// add links to items
$newForList = array();
foreach ($forList as $arItem) {
	$arItem['LINK'] = $arIBlock['LIST_PAGE_URL'].$arItem['CODE'].'/';
	$newForList[] = $arItem;
}
$forList = $newForList;

// only items that contains active elements in active sections
$forListActive = array();
foreach ($forList as $arItem) {
	if ($arItem['COUNT'] <= 0) continue;
	$forListActive[] = $arItem;
}

$curFor = false;
$curPage = $APPLICATION->GetCurPage();
$fixedPagePrefix = null;

foreach ($forList as $arItem) {
	if (stripos($curPage, $arIBlock['LIST_PAGE_URL'].$arItem['CODE'].'/') === 0) {
		$curFor = $arItem;
		$fixedPagePrefix = $arIBlock['LIST_PAGE_URL'].$arItem['CODE'].'/';
	}
}

if ($fixedPagePrefix === null) {
	$fixedPagePrefix = $arIBlock['LIST_PAGE_URL'];
}

if (strlen($fixedPagePrefix) < strlen($curPage)) {
	// prepare filter
	$arFilter = array(
		"ACTIVE" => "Y",
		"IBLOCK_TYPE" => $iblock_type,
		"IBLOCK_ID" => $iblock_id,
	);

	// get active sections list
	// will be filtered more latear by active elements
	$res = CIBlockSection::GetList(
		$arSort,
		$arFilter,
		false,
		array('UF_*')
	);

	// prepare elements filter template
	$elFilterTmpl = $arFilter;
	if ($curFor !== false) {
		$elFilterTmpl = array_merge($elFilterTmpl, array(
			'PROPERTY_FOR' => $curFor['CODE']
		));
	}

	while ($arSection = $res->GetNext()) {
		// remove section from list if it hasn't any active elements
		$elFilter = array_merge(
			array('SECTION_ID' => $arSection['ID']), $elFilterTmpl);
		$elRes = CIBlockElement::GetList(array(), $elFilter);
		if ($elRes->SelectedRowsCount() <= 0) continue;

		// if we on page filtered by "FOR" then fix links for this page
		if ($curFor) {
			$arSection['SECTION_PAGE_URL'] = str_replace(
				$arIBlock['LIST_PAGE_URL'],
				$arIBlock['LIST_PAGE_URL'].$curFor['CODE'].'/',
				$arSection['SECTION_PAGE_URL']);
		}

		$aMenuLinks[] = array(
			$arSection["NAME"],
			$arSection["SECTION_PAGE_URL"],
			array(),
			array(),
			""
		);
	}

	$aMenuLinks[] = array(
		GetMessage('ALL'),
		$fixedPagePrefix . '?show=all',
		array(),
		array(),
		''
	);
}



















return;

$dir = $APPLICATION->GetCurDir();
$page = $APPLICATION->GetCurPage();

$arDir = explode("/", $dir);
foreach($arDir as $k => $v){
	if($v === "") unset($arDir[$k]);
}

if(
	(count($arDir) > 1 || $_GET["show"] === "all") &&
	(
		$page != "/products/women/" &&
		$page != "/products/men/" &&
		$page != "/products/cildren/"
	)
){
	CModule::IncludeModule("iblock");
	$aMenuLinks = array();
	$section = CIBlockSection::GetList(
		array(
			"SORT" => "asc"
		),
		array(
			"ACTIVE" => "Y",
			"IBLOCK_CODE" => "catalog",
			"PROPERTY" => array(
				"BRAND" => false
			)
		),
		true
	);
	if($section->SelectedRowsCount()){
		while($arSection = $section->GetNExt()){
			if($arSection["ELEMENT_CNT"]){
				if(stripos($APPLICATION->GetCurDir(), "/women/"))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/women", $arSection["SECTION_PAGE_URL"]);
				if(stripos("/men/", $APPLICATION->GetCurDir()))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/men", $arSection["SECTION_PAGE_URL"]);
				if(stripos("/children/", $APPLICATION->GetCurDir()))
					$arSection["SECTION_PAGE_URL"] = str_replace("products", "products/children", $arSection["SECTION_PAGE_URL"]);

				$aMenuLinks[] = Array(
					$arSection["NAME"],
					$arSection["SECTION_PAGE_URL"],
					array(),
					array(),
					""
				);
			}
		}
		$aMenuLinks[] = Array(
			GetMessage("ALL"),
			$arDir[0] . "?show=all",
			array(),
			array(),
			""
		);
	}
}
