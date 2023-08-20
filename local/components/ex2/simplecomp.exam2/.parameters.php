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
        "USER_PROPERTY_CODE" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLE_2_USER_PROPERTY_TITLE"),
            "TYPE" => "STRING",
        ],
        "TEMPLATE_DETAIL_URL" => CIBlockParameters::GetPathTemplateParam(
            "DETAIL",
            "TEMPLATE_DETAIL_URL",
            GetMessage("SIMPLE_2_DETAIL_URL_TITLE"),
            "/catalog_exam/#SECTION_ID#/#ELEMENT_CODE#",
            "URL_TEMPLATES"
        ),
        "CACHE_TIME" => ["DEFAULT" => 36000000],
    ],
];