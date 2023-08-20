<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

?>

<?php if(!empty($arResult["ITEMS"])) { ?>
    <div>
        <?=GetMessage("SIMPLE_2_FILTER_TITLE")?> <a href="<?=$arResult["URL"]?>">/exam2/ex2-48/?F=Y</a><br>
        ---<br><br>
        <b><?=GetMessage("SIMPLE_2_CATALOG_TITLE")?></b>
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
