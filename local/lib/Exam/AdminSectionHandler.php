<?php

namespace lib\Exam;

use CUser;
use CGroup;
use lib\Constants;

/**
 * Вспомогательный класс для работы с административным разделом
 */
class AdminSectionHandler
{
    /** @var string префикс символьного кода элементов административного меню */
    public const GLOBAL_MENU = "global_menu_";

    /**
     * Упрощение левого меню административного раздела
     * @param $aGlobalMenu
     * @param $aModuleMenu
     * @return void
     */
    public static function onBuildGlobalMenuContentWriter(&$aGlobalMenu, &$aModuleMenu): void
    {
        $contentEditorGroupId = self::getUserGroupIdByCode(Constants::CONTENT_EDITOR_NAME);
        if (!$contentEditorGroupId) {
            return;
        }
        global $USER;
        if (!in_array($contentEditorGroupId, $USER->GetUserGroupArray()) || $USER->IsAdmin()) {
            return;
        }
        unset($aGlobalMenu[self::GLOBAL_MENU."desktop"]);
        foreach ($aModuleMenu as $key => $arItem) {
            if ($aModuleMenu[$key]["parent_menu"] === self::GLOBAL_MENU."settings" ||
                $aModuleMenu[$key]["parent_menu"] === self::GLOBAL_MENU."services" ||
                $aModuleMenu[$key]["parent_menu"] === self::GLOBAL_MENU."marketplace" ||
                $aModuleMenu[$key]["items_id"] === "menu_iblock") {
                unset($aModuleMenu[$key]);
            }
        }
    }

    /**
     * Получить ID группы пользователей по символьному коду
     * @param string $code
     * @return int|bool
     */
    public static function getUserGroupIdByCode(string $code): int|bool
    {
        if (!$code) {
            return false;
        }
        $rsGroup = CGroup::GetList(
            "c_sort",
            "desc",
            [
                "STRING_ID" => $code
            ]
        );
        $arGroup = $rsGroup->Fetch();
        if (!$arGroup) {
            return false;
        }
        if ($arGroup["ID"]) {
            return $arGroup["ID"];
        }
        return false;
    }
}