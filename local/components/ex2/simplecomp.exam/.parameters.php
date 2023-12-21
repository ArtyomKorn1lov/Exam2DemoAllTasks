<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    "GROUPS" => [],
    "PARAMETERS" => [
        "IBLOCK_CATALOG_ID" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLE_IBLOCK_CATALOG"),
            "TYPE" => "STRING",
        ],
        "IBLOCK_NEWS_ID" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLE_IBLOCK_NEWS"),
            "TYPE" => "STRING",
        ],
        "USER_PROPERTY_CODE" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLE_USER_PROPERTY"),
            "TYPE" => "STRING",
        ],
        "CACHE_TIME" => ["DEFAULT" => 36000000],
    ],
];