<?php

namespace lib\Exam;

use CEventLog;
use lib\Constants;

/**
 * Вспомогательный класс для записи в журнал событий
 */
class EventLogWriter
{
    /**
     * Запись в журнал событий несуществующих страниц
     * @return void
     */
    public static function onEpilogCheck404(): void
    {
        if (defined('ERROR_404') && ERROR_404 == 'Y') {
            global $APPLICATION;
            CEventLog::Add(
                [
                    "SEVERITY" => "INFO",
                    "AUDIT_TYPE_ID" => "ERROR_404",
                    "MODULE_ID" => "main",
                    "DESCRIPTION" => $APPLICATION->GetCurUri(),
                ]
            );
        }
    }

    /**
     * Замена поля AUTHOR в отсылаемом письме, с последущей записью в журнал
     * @param string $event
     * @param string $lid
     * @param array $arFields
     * @param string $message_id
     * @param array $files
     * @param string $languageId
     * @return void
     */
    public static function onBeforeEventAddChangeMailData(string &$event, string &$lid, array &$arFields,
                                                          string &$message_id, array &$files, string &$languageId): void
    {
        if ($event !== Constants::FEEDBACK_FORM_EVENT_NAME) {
            return;
        }
        global $USER;
        if ($USER->IsAuthorized()) {
            $arFields["AUTHOR"] = "Пользователь авторизован: " . $USER->GetID() . " (" . $USER->GetLogin() . ") " . $USER->GetFirstName() .
                ", данные из формы: " . $arFields["AUTHOR"];
        } else {
            $arFields["AUTHOR"] = "Пользователь не авторизован, данные из формы: " . $arFields["AUTHOR"];
        }
        $description = "Замена данных в отсылаемом письме – " . $arFields["AUTHOR"];
        CEventLog::Add(
            [
                "SEVERITY" => "INFO",
                "AUDIT_TYPE_ID" => "MAIL_CHANGE_AUTHOR",
                "MODULE_ID" => "main",
                "DESCRIPTION" => $description,
            ]
        );
    }
}