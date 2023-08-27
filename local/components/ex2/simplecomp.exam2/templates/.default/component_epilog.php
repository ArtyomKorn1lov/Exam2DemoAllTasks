<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!isset($arResult["MAX_PRICE"]) && !isset($arResult["MIN_PRICE"])) {
    return;
}
ob_start();
?>
    <div style="color:red; margin: 34px 15px 35px 15px">--- <?= $arResult["MAX_PRICE"] ?> <?= $arResult["MIN_PRICE"] ?> --- </div>
<?php
$html = ob_get_contents();
ob_end_clean();
$APPLICATION->AddViewContent("price_simple_info", $html);
