<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use lib\Exam\IBlockHelper;
use CIBlockElement;
use Bitrix\Main\Loader;

if (isset($arResult["CANONICAL_NAME"])) {
    $APPLICATION->SetPageProperty("canonical", $arResult["CANONICAL_NAME"]);
}

/** Формализация данных, полученных из запроса */
$requestId = htmlspecialchars($_REQUEST["ID"]);

/** Обработка страницы после отправки жалобы без режима ajax */
if ($_REQUEST["TYPE"] === "REPORT_RESULT") {
    echo '<script>' . PHP_EOL;
    echo 'let textElem = BX("text-error");' . PHP_EOL;
    if ($requestId) {
        echo 'textElem.innerText = "Ваше мнение учтено, №' . $requestId . '";' . PHP_EOL;
    } else {
        echo 'textElem.innerText = "Ошибка";' . PHP_EOL;
    }
    echo '</script>';
/** Обработка запроса на сервере */
} else {
    if (isset($_REQUEST["ID"])) {
        $arAnswer = [];
        /** Добавление жалобы в ИБ Жалобы на новости */
        $iElementId = IBlockHelper::addUserReport($requestId);
        /** Ошибка добавления пользователя в ИБ Жалобы на новости */
        if (!$iElementId) {
            LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT");
            exit;
        }
        $arAnswer['ID'] = $iElementId;
        /** Формирование результата для ajax запроса */
        if ($_REQUEST['TYPE'] === 'REPORT_AJAX') {
            $APPLICATION->RestartBuffer();
            echo json_encode($arAnswer);
            exit;
        /** Формирование результата для обычного запроса */
        } elseif ($_REQUEST['TYPE'] === 'REPORT_GET') {
            LocalRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT&ID=" . $arAnswer['ID']);
            exit;
        }
    }
}