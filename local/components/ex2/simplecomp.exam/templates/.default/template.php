<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

?>
<?php if(count($arResult["ITEMS"]) > 0) { ?>
<div>
    ---<br><br>
    <b><?=GetMessage("SIMPLE_CATALOG_TITLE")?></b>
    <ul>
        <?php foreach ($arResult["ITEMS"] as $item) { ?>
            <li>
                <b><?=$item["NAME"]?></b> - <?=$item["DATE_ACTIVE_FROM"]?>
                (<?php foreach ($item["SECTIONS"]["NAMES"] as $key => $section) { ?><?php if ($key == count($item["SECTIONS"]["NAMES"]) - 1) { ?><?=$section?><?php } else { ?><?=$section?>, <?php } ?><?php } ?>)
                <ul>
                    <?php foreach ($item["CATALOG_ITEMS"] as $catalogItem) { ?>
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
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>
    </ul>
</div>
<?php } ?>
