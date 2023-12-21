<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arParams["SPECIAL_DATE"]) && $arParams["SPECIAL_DATE"] == "Y" && count($arResult["ITEMS"]) > 0) {
            $arResult["SPECIAL_DATE"] = $arResult["ITEMS"][0]["DISPLAY_ACTIVE_FROM"];
            $this->getComponent()->SetResultCacheKeys(["SPECIAL_DATE"]);
}