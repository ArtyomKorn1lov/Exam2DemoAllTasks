<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init(["ajax"]);
?>
<div class="news-detail">
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
		<img class="detail_picture" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>"  title="<?=$arResult["NAME"]?>" />
	<?endif?>
	<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<div class="news-date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></div>
	<?endif;?>
	<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h3><?=$arResult["NAME"]?></h3>
	<?endif;?>
    <?php if ($arParams["AJAX_MODE_COMPLAINTS"] === "Y") { ?>
        <a id="ajax-link" href="" onclick="return false;"><?=GetMessage("REPORT_TITLE")?></a></br>
        <script>
            /** Установка событие клика на ссылку, отправка ajax запроса на сервер */
            BX.ready(function () {
                let ajaxLink = BX("ajax-link");
                let errorElement = BX("text-error");
                BX.bind(ajaxLink, 'click', function () {
                    BX.ajax.loadJSON(
                        '<?=$APPLICATION->GetCurPage();?>',
                        {"TYPE": "REPORT_AJAX", "ID": "<?=$arResult["ID"]?>"},
                        function (data) {
                            errorElement.innerHTML = "<?=GetMessage("SUCESS_TITLE")?>" + data["ID"];
                        },
                        function (data) {
                            errorElement.innerHTML = "<?=GetMessage("ERROR_TITLE")?>";
                        }
                    );
                });
            });
        </script>
    <?php } else { ?>
        <a href="<?=$APPLICATION->GetCurPage();?>?TYPE=REPORT_GET&ID=<?=$arResult["ID"]?>"><?=GetMessage("REPORT_TITLE")?></a></br>
    <?php } ?>
    <span id="text-error"></span>
	<div class="news-detail">
	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?endif;?>
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
 	<?elseif($arResult["DETAIL_TEXT"] <> ''):?>
		<?echo $arResult["DETAIL_TEXT"];?>
 	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	<div style="clear:both"></div>
	<br />
	<?foreach($arResult["FIELDS"] as $code=>$value):?>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			<br />
	<?endforeach;?>
	<?foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>

		<?=$arProperty["NAME"]?>:&nbsp;
		<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
			<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
		<?else:?>
			<?=$arProperty["DISPLAY_VALUE"];?>
		<?endif?>
		<br />
	<?endforeach;?>
	</div>
</div>
