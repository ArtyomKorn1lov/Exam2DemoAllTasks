<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => GetMessage("SIMPLE_2_NAME"),
    "DESCRIPTION" => GetMessage("SIMPLE_2_DESCRIPTION"),
    "PATH" => [
        "ID" => "content",
        "CHILD" => [
            "ID" => "catalog",
            "NAME" => GetMessage("SIMPLE_2_CATALOG_NAME"),
        ]
    ],
];