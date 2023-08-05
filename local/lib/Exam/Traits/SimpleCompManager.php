<?php

namespace lib\Exam\Traits;

use CIBlockElement;
use CIBlockSection;

/**
 * Трейт для получения данных для компонента "Каталог товаров"
 */
trait SimpleCompManager
{
    /**
     * Получить список ИБ новости
     * @param int $iblockId
     * @return array|bool
     */
    public static function getNewsForSimpleComp(int $iblockId): array|bool
    {
        if ($iblockId <= 0) {
            return false;
        }
        $obItems = CIBlockElement::GetList(
            ["ID" => "DESC", "SORT" => "DESC"],
            ["IBLOCK_ID" => $iblockId],
            false,
            false,
            ["NAME", "DATE_ACTIVE_FROM"]
        );
        $arNews = [];
        while ($item = $obItems->Fetch()) {
            $arNews[] = $item;
        }
        return $arNews;
    }

    /**
     * Установить в результирующий список разделы ИБ продукция, приявязанные к ИБ новости
     * @param int $iblockId
     * @param string $userPropertyCode
     * @param array $arNews
     * @return array|bool
     */
    public static function setCatalogSectionsForSimpleComp(int $iblockId, string $userPropertyCode, array $arNews): array|bool
    {
        if ($iblockId <= 0) {
            return false;
        }
        if (count($arNews) <= 0) {
            return false;
        }
        $obItems = CIBlockSection::GetList(
            ["ID" => "DESC", "SORT" => "DESC"],
            ["IBLOCK_ID" => $iblockId],
            false,
            [$userPropertyCode, "NAME"],
            false
        );
        $arCatalogSections = [];
        while ($item = $obItems->Fetch()) {
            $arCatalogSections[] = $item;
        }
        if (count($arCatalogSections) <= 0) {
            return false;
        }
        foreach ($arNews as $key => $new) {
            foreach ($arCatalogSections as $catalogSection) {
                if (in_array($new["ID"], $catalogSection[$userPropertyCode])) {
                    $arNews[$key]["SECTIONS"]["IDS"][] = $catalogSection["ID"];
                    $arNews[$key]["SECTIONS"]["NAMES"][] = $catalogSection["NAME"];
                }
            }
        }
        return $arNews;
    }

    /**
     * Установить в результирующий список элементы ИБ продукция, привязанные к разделам
     * @param int $iblockId
     * @param array $arResult
     * @return array|bool
     */
    public static function setCatalogItemsForSimpleComp(int $iblockId, array $arResult): array|bool
    {
        if ($iblockId <= 0) {
            return false;
        }
        if (count($arResult) <= 0) {
            return false;
        }
        $arResult["PRODUCT_COUNT"] = 0;
        foreach ($arResult as $key => $arItem) {
            $obItems = CIBlockElement::GetList(
                ["ID" => "DESC", "SORT" => "DESC"],
                ["IBLOCK_ID" => $iblockId, "SECTION_ID" => $arItem["SECTIONS"]["IDS"]],
                false,
                false,
                ["NAME", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER", "PROPERTY_PRICE"]
            );
            if (!$obItems) {
                continue;
            }
            while ($item = $obItems->Fetch()) {
                $arResult[$key]["CATALOG_ITEMS"][] = $item;
                $arResult["PRODUCT_COUNT"]++;
            }
        }
        return $arResult;
    }
}