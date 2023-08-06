<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент 2");
?><?$APPLICATION->IncludeComponent(
	"ex2:simplecomp.exam2",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"DETAIL_URL" => "",
		"IBLOCK_CATALOG_ID" => "2",
		"IBLOCK_CLASSIFIRE_ID" => "7",
		"USER_PROPERTY_CODE" => "FIRM"
	)
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>