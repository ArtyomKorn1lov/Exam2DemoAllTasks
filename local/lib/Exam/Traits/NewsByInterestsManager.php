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
    public static function getUsers(int $authorType, string $userPropertyCode): array|bool
    {
        if ($authorType <= 0 || strlen($userPropertyCode) <= 0) {
            return false;
        }
        $obItems = CUser::GetList(
            "ID",
            "DESC",
            [$userPropertyCode => $authorType],
            ["FIELDS" => ["LOGIN", "ID"]]
        );
        $arResult["ITEMS"] = [];
        while ($item = $obItems->Fetch()) {
          $arResult["ITEMS"][] = $item;  
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
    public static function setNewsToResult(int $iblockId, string $iblockPropertyCode, array $arResult): array|bool
    {
        if ($iblockId <= 0 || strlen($iblockPropertyCode) <= 0) {
            return false;
        }
        $arNews = [];
        $arResult["UNIC_COUNT"] = 0;
        foreach ($arResult["ITEMS"] as $key => $arItem) {
            $obItems = CIBlockElement::GetList(
                ["ID" => "DESC", "SORT" => "DESC"],
                ["IBLOCK_ID" => $iblockId, "PROPERTY_".$iblockPropertyCode => $arItem["ID"]],
                false,
                false,
                ["NAME", "ACTIVE_FROM"]
            );
            while ($item = $obItems->Fetch()) {
                $arResult["ITEMS"][$key]["NEWS"][] = $item;
                if (!in_array($item["ID"], $arNews, true)) {
                    $arNews[] = $item["ID"];
                    $arResult["UNIC_COUNT"]++;
                }
            }
        }
        return $arResult;
    }
}