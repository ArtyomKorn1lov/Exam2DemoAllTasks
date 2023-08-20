<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

?>

<?php if(!empty($arResult["ITEMS"])) { ?>
    <div>
        ---<br><br>
        <b>Каталог:</b>
        <ul>
            <?php foreach ($arResult["ITEMS"] as $item) { ?>
                <li>
                    <b><?=$item["NAME"]?></b>
                    <ul>
                        <?php foreach ($item["PRODUCTS"] as $catalogItem) { ?>
                            <li>
                                <?php if (strlen($catalogItem["NAME"]) > 0) { ?>
                                    <?=$catalogItem["NAME"]?>
                                <?php } ?>
                                <?php if (strlen($catalogItem["NAME"]) > 0) { ?>
                                    - <?=$catalogItem["PROPERTY_PRICE_VALUE"]?>
                                <?php } ?>
                                <?php if (strlen($catalogItem["NAME"]) > 0) { ?>
                                    - <?=$catalogItem["PROPERTY_MATERIAL_VALUE"]?>
                                <?php } ?>
                                <?php if (strlen($catalogItem["NAME"]) > 0) { ?>
                                    - <?=$catalogItem["PROPERTY_ARTNUMBER_VALUE"]?>
                                <?php } ?>
                                <?php if (strlen($catalogItem["DETAIL_PAGE_URL"]) > 0) { ?>
                                    (<?= $catalogItem["DETAIL_PAGE_URL"] ?>)
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
