<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент 2");
?><?$APPLICATION->IncludeComponent(
	"ex2:simplecomp.exam2", 
	".default", 
	array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"DETAIL_URL" => "/catalog_exam/#SECTION_ID#/#ELEMENT_CODE#",
		"IBLOCK_CATALOG_ID" => "2",
		"IBLOCK_CLASSIFIRE_ID" => "7",
		"USER_PROPERTY_CODE" => "FIRM",
		"COMPONENT_TEMPLATE" => ".default",
		"TEMPLATE_DETAIL_URL" => "/catalog_exam/#SECTION_ID#/#ELEMENT_CODE#"
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>