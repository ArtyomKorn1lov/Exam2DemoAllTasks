<?php

namespace lib\Exam;

use CIBlockElement;

/**
 * Вспомогательный класс для работы с ИБ
 */
class IBlockHelper
{
    /**
     * Получение элемент ИБ по ID внешнего элемента
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
}