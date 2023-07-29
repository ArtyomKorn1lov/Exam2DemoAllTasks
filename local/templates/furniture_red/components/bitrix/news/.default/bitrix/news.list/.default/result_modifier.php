<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arParams["SPECIAL_DATE"])) {
    if ($arParams["SPECIAL_DATE"] == "Y") {
        if (count($arResult["ITEMS"]) > 0) {
            $arResult["SPECIAL_DATE"] = $arResult["ITEMS"][0]["DISPLAY_ACTIVE_FROM"];
            $this->__component->SetResultCacheKeys(["SPECIAL_DATE"]);
        }
    }
}