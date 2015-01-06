<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule('highloadblock')) return;

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$getForList = function ($iblock_type, $iblock_id) {
	// find "FOR" property
	$res = CIBlockProperty::GetByID('FOR', $iblock_id);
	$arProp = $res->GetNext();
	if (!$arProp) return 'PROP_FOR_NOT_FOUND';

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
