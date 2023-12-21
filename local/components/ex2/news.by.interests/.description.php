<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => GetMessage("INTERESTS_NAME"),
    "DESCRIPTION" => GetMessage("INTERESTS_NAME"),
    "PATH" => [
        "ID" => "content",
        "CHILD" => [
            "ID" => "news",
            "NAME" => GetMessage("INTERESTS_COMP_NAME"),
        ]
    ],
];
