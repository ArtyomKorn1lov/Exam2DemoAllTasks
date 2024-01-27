<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arTemplateParameters = [
    "SPECIAL_DATE" => [
        "PARENT" => "BASE",
        "NAME" => GetMessage("NEWS_SPECIAL_DATE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
    ],
    "IBLOCK_ID_FOR_CANONICAL" => [
        "PARENT" => "BASE",
        "NAME" => GetMessage("NEWS_CANONICAL"),
        "TYPE" => "STRING",
    ],
    "AJAX_MODE_COMPLAINTS" => [
        "PARENT" => "BASE",
        "NAME" => GetMessage("AJAX_MODE_COMPLAINTS_TITLE"),
        "TYPE" => "CHECKBOX",
    ],
];