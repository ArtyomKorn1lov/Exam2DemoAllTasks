<?php

namespace lib\Exam\Traits;

use CUser;
use CIBlockElement;

/**
 * Трейт для получения данных для компонента "Новости по интересам"
 */
trait NewsByInterestsManager
{
    /**
     * Получение списка пользователей одного типа авторов
     * @param int $authorType
     * @param string $userPropertyCode
     * @return array|bool
     */
    public static function getUsers(int $authorType, string $userPropertyCode, int $userId): array|bool
    {
        if ($authorType <= 0 || strlen($userPropertyCode) <= 0) {
            return false;
        }
        $obItems = CUser::GetList(
            "ID",
            "DESC",
            [$userPropertyCode => $authorType, "!ID" => $userId],
            ["FIELDS" => ["LOGIN", "ID"]]
        );
        $arResult["ITEMS"] = [];
        while ($item = $obItems->Fetch()) {
            $arResult["ITEMS"][] = $item;
            $arResult["IDS"][] = $item["ID"];
        }
        return $arResult;
    }

    /**
     * Установка новостей, привязанных к пользователям, в результирующий массив
     * @param int $iblockId
     * @param string $iblockPropertyCode
     * @param array $arResult
     * @return array|bool
     */
    public static function setNewsToResult(int $iblockId, string $iblockPropertyCode, array $arResult, int $userId): array|bool
    {
        if ($iblockId <= 0 || strlen($iblockPropertyCode) <= 0) {
            return false;
        }
        $arResult["UNIC_COUNT"] = 0;
        $obItems = CIBlockElement::GetList(
            ["ID" => "DESC", "SORT" => "DESC"],
            ["IBLOCK_ID" => $iblockId, "PROPERTY_".$iblockPropertyCode => $arResult["IDS"], "!PROPERTY_".$iblockPropertyCode => $userId],
            false,
            false,
            ["ID", "IBLOCK_ID", "NAME", "ACTIVE_FROM"]
        );
        while ($object = $obItems->GetNextElement()) {
            $item = $object->GetFields();
            $item["PROPS"] = $object->GetProperty("AUTHOR");
            $arResult["UNIC_COUNT"]++;
            foreach ($arResult["ITEMS"] as $key => $arItem) {
                if ($arItem["ID"] >= 0 && !empty($item["PROPS"]["VALUE"]) && in_array($arItem["ID"], $item["PROPS"]["VALUE"])) {
                    $arResult["ITEMS"][$key]["NEWS"][] = $item;
                }
            }
        }
        return $arResult;
    }
}