<?php

namespace lib\Exam;

use lib\Constants;
use CIBlockElement;
use Bitrix\Iblock\IblockTable;

/**
 * Вспомогательный класс для работы с ИБ
 */
class IBlockHelper
{
    /**
     * Получение элемента ИБ по ID внешнего элемента
     * @param int $id
     * @param int $iblockId
     * @param string $propCode
     * @return array|bool
     */
    public static function getElementByExternalId(int $id, int $iblockId, string $propCode): array|bool
    {
        if ($id <= 0 || $iblockId <= 0 || !$propCode) {
            return false;
        }
        $obItems = CIBlockElement::GetList(
            ["ID" => "DESC", "SORT" => "DESC"],
            ["IBLOCK_ID" => $iblockId, "PROPERTY_".$propCode => $id],
            false,
            false,
            ["NAME"]
        );
        $item = $obItems->Fetch();
        if ($item) {
            return $item;
        }
        return false;
    }

    /**
     * Проверка количества просмотров при деактивации товара ИБ Продукция
     * @param array $arFields
     * @return bool
     */
    public static function onBeforeIBlockProductElementDeactivate(array &$arFields): bool
    {
        global $APPLICATION;
        $iblockId = IblockTable::getRow(['filter' => ['CODE' => Constants::IBLOCK_CODE_PRODUCTS]])["ID"];
        if (!isset($iblockId)) {
            $APPLICATION->throwException("Инфоблока с данным символьным кодом не существует");
            return false;
        }
        if ((int)$iblockId !== $arFields["IBLOCK_ID"]) {
            return true;
        }
        if (!$arFields["ID"] || (int)$arFields["ID"] <= 0) {
            $APPLICATION->throwException("ID товара не существует");
            return false;
        }
        $obResult = CIBlockElement::GetList(
            ["ID" => "DESC", "SORT" => "DESC"],
            ["IBLOCK_ID" => $arFields["IBLOCK_ID"], "ID" => $arFields["ID"]],
            false,
            false,
            ["SHOW_COUNTER"],
        );
        $result = $obResult->GetNext();
        if (!$result) {
            $APPLICATION->throwException("ID товара не существует");
            return false;
        }
        if ($result["SHOW_COUNTER"] > 2 && $arFields["ACTIVE"] === "N") {
            $APPLICATION->throwException("Товар невозможно деактивировать, у него [".$result["SHOW_COUNTER"]."] просмотров");
            return false;
        }
        return true;
    }
}