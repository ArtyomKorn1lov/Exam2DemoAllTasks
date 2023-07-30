<?php

namespace lib\Exam;

use CEventLog;

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
            $APPLICATION->RestartBuffer();
            include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php";
            include $_SERVER["DOCUMENT_ROOT"] . "/404.php";
            include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php";
            CEventLog::Add(
                [
                    "SEVERITY" => "INFO",
                    "AUDIT_TYPE_ID" => "ERROR_404",
                    "MODULE_ID" => "main",
                    "DESCRIPTION" => $APPLICATION->GetCurPage(),
                ]
            );
        }
    }
}