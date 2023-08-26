<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<?php if(!empty($arResult["ITEMS"])) { ?>
    <div>
        <?=GetMessage("SIMPLE_2_FILTER_TITLE")?> <a href="<?=$arResult["URL"]?>">/exam2/ex2-48/?F=Y</a><br>
        ---<br><br>
        <b><?=GetMessage("SIMPLE_2_CATALOG_TITLE")?></b>
        <?php $this->AddEditAction("add_element", $arResult['ADD_LINK'], $arResult["ADD_LINK_TEXT"]); ?>
        <ul id="<?=$this->GetEditAreaId("add_element");?>">
            <?php foreach ($arResult["ITEMS"] as $item) { ?>
                <li>
                    <b><?=$item["NAME"]?></b>
                    <ul>
                        <?php foreach ($item["PRODUCTS"] as $catalogItem) {
                            $this->AddEditAction($item["ID"]."_".$catalogItem['ID'], $catalogItem['EDIT_LINK'], $catalogItem["EDIT_LINK_TEXT"]);
                            $this->AddDeleteAction($item["ID"]."_".$catalogItem['ID'], $catalogItem['DELETE_LINK'], $catalogItem["DELETE_LINK_TEXT"], array("CONFIRM" => GetMessage('SIMPLE_2_DELETE_CONFIRM')));
                            ?>
                            <li id="<?=$this->GetEditAreaId($item["ID"]."_".$catalogItem['ID']);?>">
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
