<?php

namespace lib\Exam\Traits;

use CIBlock;
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
        foreach ($arCatalogSections as $catalogSection) {
            $arNews["SECTION_IDS"][] = $catalogSection["ID"];
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
        $obItems = CIBlockElement::GetList(
            ["ID" => "DESC", "SORT" => "DESC"],
            ["IBLOCK_ID" => $iblockId, "SECTION_ID" => $arResult["SECTION_IDS"]],
            false,
            false,
            ["IBLOCK_SECTION_ID", "NAME", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER", "PROPERTY_PRICE"]
        );
        while ($item = $obItems->Fetch()) {
            foreach ($arResult as $key => $arItem) {
                if (!empty($arItem["SECTIONS"]["IDS"]) && in_array($item["IBLOCK_SECTION_ID"], $arItem["SECTIONS"]["IDS"])) {
                    $arResult[$key]["CATALOG_ITEMS"][] = $item;
                }
            }
            $arResult["PRODUCT_COUNT"]++;
        }
        return $arResult;
    }

    /**
     * Получить классификаторы из ИБ Фирма
     * @param int $iblockId
     * @return array|bool
     */
    public static function getProductClassifireForSimpleComp(int $iblockId, int $nTopCount, int $page, string $messPagination): array|bool
    {
        if ($iblockId <= 0) {
            return false;
        }
        $arPagination = false;
        if ($nTopCount > 0) {
            $arPagination = [
                "nPageSize" => $nTopCount,
                "iNumPage" => $page
            ];
        }
        $obItems = CIBlockElement::GetList(
            ["ID" => "DESC", "SORT" => "DESC"],
            ["IBLOCK_ID" => $iblockId],
            false,
            $arPagination,
            ["NAME"]
        );
        $arResult["NAV_STRING"] = self::getNavString($obItems, $messPagination);
        $arResult["ITEMS"] = [];
        $arResult["SECTION_COUNT"] = 0;
        while ($item = $obItems->Fetch()) {
            $arResult["ITEMS"][] = $item;
            $arResult["PRODUCT_IDS"][] = $item["ID"];
            $arResult["SECTION_COUNT"]++;
        }
        return $arResult;
    }

    /**
     * Установка товаров из ИБ Продукция для каждого из классификатор в результирующий список
     * @param int $iblockId
     * @param string $propCode
     * @param array $arResult
     * @return array|bool
     */
    public static function setProductListForSimpleComp(int $iblockId, string $propCode, string $templateDetailUrl, bool $cFilter, array $arResult): array|bool
    {
        if ($iblockId <= 0) {
            return false;
        }
        if (count($arResult["ITEMS"]) <= 0) {
            return false;
        }
        $arFiler = ["IBLOCK_ID" => $iblockId, "PROPERTY_".$propCode => $arResult["PRODUCT_IDS"]];
        if ($cFilter) {
            $arFiler[] = [
                "LOGIC" => "OR",
                ["<=PROPERTY_PRICE" => 1700, "PROPERTY_MATERIAL" => "Дерево, ткань"],
                ["<PROPERTY_PRICE" => 1500, "PROPERTY_MATERIAL" => "Металл, пластик"],
            ];
        }
        $obItems = CIBlockElement::GetList(
            ["NAME" => "ASC", "SORT" => "ASC"],
            $arFiler,
            false,
            false,
            ["ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER", "PROPERTY_PRICE", "DETAIL_PAGE_URL"]
        );
        if (isset($templateDetailUrl)) {
            $obItems->SetUrlTemplates($templateDetailUrl);
        }
        while ($object = $obItems->GetNextElement()) {
            $item = $object->GetFields();
            $arButtons = CIBlock::GetPanelButtons(
                $iblockId,
                $item["ID"],
                0,
                ["SECTION_BUTTONS" => false, "SESSID" => false]
            );
            $item["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
            $item["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
            $item["EDIT_LINK_TEXT"] = $arButtons["edit"]["edit_element"]["TEXT"];
            $item["DELETE_LINK_TEXT"] = $arButtons["edit"]["delete_element"]["TEXT"];
            if (!(isset($arResult["ADD_LINK"]) && isset($arResult["ADD_LINK_TEXT"]))) {
                $arResult["ADD_LINK"] = $arButtons["edit"]["add_element"]["ACTION_URL"];
                $arResult["ADD_LINK_TEXT"] = $arButtons["edit"]["add_element"]["TEXT"];
            }
            $item["PROPS"] = $object->GetProperty("FIRM");
            foreach ($arResult["ITEMS"] as $key => $arItem) {
                if ($arItem["ID"] >= 0 && !empty($item["PROPS"]["VALUE"]) && in_array($arItem["ID"], $item["PROPS"]["VALUE"])) {
                    $arResult["ITEMS"][$key]["PRODUCTS"][] = $item;
                }
            }
        }
        return $arResult;
    }

    /**
     * Установка минимальной и максимальной цены в списке товаров
     * @param array $arResult
     * @return array|bool
     */
    public static function getMinAndMaxPrice(array $arResult): array|bool
    {
        if (count($arResult["ITEMS"]) <= 0) {
            return false;
        }
        $arPrice = self::mergeProductArray($arResult);
        $arResult["MAX_PRICE"] = max($arPrice);
        $arResult["MIN_PRICE"] = min($arPrice);
        return $arResult;
    }

    /**
     * Создание сприска с ценами товаров
     * @param array $arResult
     * @return array
     */
    private static function mergeProductArray(array $arResult): array
    {
        $arProducts = [];
        foreach ($arResult["ITEMS"] as $arItem) {
            $arProducts = array_merge($arProducts, $arItem["PRODUCTS"]);
        }
        $arPrice = [];
        foreach ($arProducts as $arItem) {
            $arPrice[] = $arItem["PROPERTY_PRICE_VALUE"];
        }
        return $arPrice;
    }

    /**
     * Получить HTML разметку для пагинации
     * @param object $rsObject
     * @return string
     */
    private static function getNavString(object $rsObject, string $messPagination): string
    {
        /** @var TYPE_NAME $navComponentObject */
        $arNavString = $rsObject->GetPageNavStringEx(
            $navComponentObject,
            $messPagination,
            "",
            "Y"
        );
        return $arNavString;
    }
}