<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$getForList = function ($this, $arParams) {
	$res = CIBlockProperty::GetByID('FOR', $arParams["IBLOCK_ID"]);
	$arProp = $res->GetNext();
	if (!$arProp) {
		ShowError(GetMessage('PROP_FOR_NOT_FOUND'));
		CHTTP::SetStatus('500 Internal Server Error');
		$this->AbortResultCache();
		return false;
	}
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

		$elRes = CIBlockElement::GetList(
			array(),
			array(
				"ACTIVE" => "Y",
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"PROPERTY_FOR" => $newRow['CODE'],
			),
			false,
			array('nPageSize' => 5000)
			);

		while ($arEl = $elRes->GetNext()) {
			if (!$arEl['IBLOCK_SECTION_ID']) continue;
			$sRes = CIBlockSection::GetByID($arEl['IBLOCK_SECTION_ID']);
			$arSection = $sRes->GetNext();
			if ($arSection['ACTIVE'] != 'Y') continue;

			$newRow['COUNT']++;
		}

		$rows[] = $newRow;
	}

	return $rows;
};
