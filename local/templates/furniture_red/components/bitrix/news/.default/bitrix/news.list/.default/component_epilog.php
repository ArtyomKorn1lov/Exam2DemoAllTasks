<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arResult["SPECIAL_DATE"])) {
    $APPLICATION->SetPageProperty("specialdate",  $arResult["SPECIAL_DATE"]);
}