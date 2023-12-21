<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use lib\Exam\IBlockHelper;

if ($arParams["IBLOCK_ID_FOR_CANONICAL"]) {
    $result = IBlockHelper::getElementByExternalId($arResult["ID"], $arParams["IBLOCK_ID_FOR_CANONICAL"], "NEW");
    if ($result) {
        $arResult["CANONICAL_NAME"] = $result["NAME"];
        $this->__component->SetResultCacheKeys(["CANONICAL_NAME"]);
    }
}