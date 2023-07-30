<?php
$eventManager = \Bitrix\Main\EventManager::getInstance();

/** Регистрация обработчика события OnBeforeIBlockElementUpdate */
$eventManager->addEventHandler(
    "iblock",
    "OnBeforeIBlockElementUpdate",
    ["lib\\Exam\\IBlockHelper", "onBeforeIBlockProductElementDeactivate"]
);