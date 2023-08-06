<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock'))
{
    return;
}

$arComponentParameters = [
    "GROUPS" => [],
    "PARAMETERS" => [
        "IBLOCK_CATALOG_ID" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLE_2_IBLOCK_CATALOG_TITLE"),
            "TYPE" => "STRING",
        ],
        "IBLOCK_CLASSIFIRE_ID" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLE_2_IBLOCK_CLASSIFIRE_TITLE"),
            "TYPE" => "STRING",
        ],
        "DETAIL_URL" => CIBlockParameters::GetPathTemplateParam(
            "DETAIL",
            "DETAIL_URL",
            GetMessage("SIMPLE_2_DETAIL_URL_TITLE"),
            "",
            "URL_TEMPLATES"
        ),
        "USER_PROPERTY_CODE" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLE_2_USER_PROPERTY_TITLE"),
            "TYPE" => "STRING",
        ],
        "CACHE_TIME" => ["DEFAULT" => 36000000],
    ],
];