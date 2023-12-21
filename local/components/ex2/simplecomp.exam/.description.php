<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => GetMessage("SIMPLE_NAME"),
    "DESCRIPTION" => GetMessage("SIMPLE_NAME"),
    "PATH" => [
        "ID" => "content",
        "CHILD" => [
            "ID" => "catalog",
            "NAME" => GetMessage("SIMPLE_CATALOG"),
        ]
    ],
];