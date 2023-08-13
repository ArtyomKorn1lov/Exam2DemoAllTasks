<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новости по интересам");
?><?$APPLICATION->IncludeComponent(
	"ex2:news.by.interests",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"IBLOCK_NEWS_ID" => "1",
		"IBLOCK_PROPERTY_CODE" => "AUTHOR",
		"USER_PROPERTY_CODE" => "UF_AUTHOR_TYPE"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>