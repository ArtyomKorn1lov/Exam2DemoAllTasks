<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

?>
<?php if(!empty($arResult["ITEMS"])) { ?>
    <div>
        <br><br>
        <b><?=GetMessage("INTERESTS_NEWS_N_AUTHORS")?></b>
        <ul>
            <?php foreach ($arResult["ITEMS"] as $item) { ?>
                <li>
                    [<?=$item["ID"]?>] - <?=$item["LOGIN"]?>
                    <ul>
                        <?php foreach ($item["NEWS"] as $newItem) { ?>
                            <li>
                                <?php if (strlen($newItem["NAME"]) > 0) { ?>
                                    - <?=$newItem["NAME"]?>
                                <?php } ?>
                                <?php if (strlen($newItem["ACTIVE_FROM"]) > 0) { ?>
                                    - <?=$newItem["ACTIVE_FROM"]?>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
