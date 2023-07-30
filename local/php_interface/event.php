<?php
$eventManager = \Bitrix\Main\EventManager::getInstance();

/** Регистрация обработчика события OnBeforeIBlockElementUpdate для обработки события деактивация товара ИБ продукция */
$eventManager->addEventHandler(
    "iblock",
    "OnBeforeIBlockElementUpdate",
    ["lib\\Exam\\IBlockHelper", "onBeforeIBlockProductElementDeactivate"]
);

/** Регистрация обработчика события OnEpilog для обработки события появления страницы 404 */
$eventManager->addEventHandler(
    "main",
    "OnEpilog",
    ["lib\\Exam\\EventLogWriter", "onEpilogCheck404"]
);

/** Регистрация обработчика события OnBeforeEventAdd для обработки события изменения данных письма */
$eventManager->addEventHandler(
    "main",
    "OnBeforeEventAdd",
    ["lib\\Exam\\EventLogWriter", "onBeforeEventAddChangeMailData"]
);

/** Регистрация обработчика события OnBeforeEventAdd для обработки события изменения данных письма */
$eventManager->addEventHandler(
    "main",
    "OnBuildGlobalMenu",
    ["lib\\Exam\\AdminSectionHandler", "onBuildGlobalMenuContentWriter"]
);