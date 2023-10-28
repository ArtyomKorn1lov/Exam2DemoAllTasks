<?php

namespace lib\Exam;

use lib\Constants;
use CIBlockElement;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Loader;
use lib\Exam\Traits\NewsByInterestsManager;
use lib\Exam\Traits\SimpleCompManager;

/**
 * Вспомогательный класс для работы с ИБ
 */
class IBlockHelper
{
    /** Использование трейта для работы с компонентом "Каталог товаров" */
    use SimpleCompManager;
    /** Использование трейта для работы с компонентом "Новости по интересам" */
    use NewsByInterestsManager;

    /**
     * Получить ID ИБ по коду
     * @param string $code
     * @return int
     */
    public static function getIblockIdByCode(string $code): int
    {
        return IblockTable::getRow(['filter' => ['CODE' => $code]])["ID"];
    }

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
        $iblockId = self::getIblockIdByCode(Constants::IBLOCK_CODE_PRODUCTS);
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

    /**
     * Поиск метаданыых по URL страницы и их установка
     * @return bool
     */
    public static function onPageStartFindMetaTagsByPageURL(): bool
    {
        if (!Loader::includeModule('iblock')) {
            return false;
        }
        global $APPLICATION;
        $iblockId = self::getIblockIdByCode(Constants::IBLOCK_CODE_METATAGS);
        if (!isset($iblockId)) {
            return false;
        }
        $obResult = CIBlockElement::GetList(
            ["ID" => "DESC", "SORT" => "DESC"],
            ["IBLOCK_ID" => $iblockId, "NAME" => $APPLICATION->GetCurPage()],
            false,
            false,
            ["NAME", "PROPERTY_PROP_TITLE", "PROPERTY_PROP_DESCRIPTION"],
        );
        $arTags = $obResult->Fetch();
        if (!$arTags) {
            return false;
        }
        $APPLICATION->SetPageProperty("title", $arTags["PROPERTY_PROP_TITLE_VALUE"]);
        $APPLICATION->SetPageProperty("description", $arTags["PROPERTY_PROP_DESCRIPTION_VALUE"]);
        return true;
    }

    /**
     * Добавить жалобу в ИБ Жалобы на новости
     * @param int $requestId
     * @return int|bool
     */
    public static function addUserReport(int $requestId): int|bool
    {
        if (!Loader::includeModule('iblock')) {
            return false;
        }
        global $USER;
        global $APPLICATION;
        $iblockId = self::getIblockIdByCode(Constants::IBLOCK_CODE_COMPLAINS);
        if (!isset($iblockId)) {
            $APPLICATION->throwException("Инфоблока с данным символьным кодом не существует");
            return false;
        }
        $sUser = '';
        if ($USER->IsAuthorized()) {
            $sUser = $USER->GetID() . " (" . $USER->GetLogin() . ") " . $USER->GetFullName();
        } else {
            $sUser = "Не авторизован";
        }
        $iblockEntity = new CIBlockElement();
        return $iblockEntity->Add([
            'IBLOCK_ID'       => $iblockId,
            'NAME'            => 'Новость ' . $requestId,
            'ACTIVE_FROM'     => ConvertTimeStamp(time(), "FULL"),
            'PROPERTY_VALUES' => [
                'USER' => $sUser,
                'NEW' => $requestId,
            ],
        ]);
    }
}