<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    "GROUPS" => [],
    "PARAMETERS" => [
        "IBLOCK_NEWS_ID" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("INTERESTS_NEWS_ID"),
            "TYPE" => "STRING",
        ],
        "IBLOCK_PROPERTY_CODE" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("INTERESTS_IBLOCK_PROPERTY"),
            "TYPE" => "STRING",
        ],
        "USER_PROPERTY_CODE" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("INTERESTS_USER_PROPERTY"),
            "TYPE" => "STRING",
        ],
        "CACHE_TIME" => ["DEFAULT" => 36000000],
    ],
];