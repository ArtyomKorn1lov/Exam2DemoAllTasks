<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use CBitrixComponent;
use CIBlockl;
use Bitrix\Main\Loader;
use lib\Exam\IBlockHelper;
use lib\Constants;

/**
 * Класс компонента "Каталог товаров"
 */
class SimpleComponent2 extends CBitrixComponent
{
    /**
     * Основной функционал компонента
     * @return void
     */
    public function executeComponent(): void
    {
        try {
            if (!$this->includeModules()) {
                return;
            }
            $this->completeComponent();
        }
        catch (Exception $exception) {
            ShowError($exception);
        }
    }

    /**
     * Проверка подключения модулей
     * @return bool
     */
    private function includeModules(): bool
    {
        if(!Loader::includeModule("iblock"))
        {
            ShowError(GetMessage("SIMPLE_2_IBLOCK_MODULE_NOT_FOUND"));
            throw new Exception(GetMessage("SIMPLE_2_IBLOCK_MODULE_NOT_FOUND"));
        }
        return true;
    }

    /**
     * Подготовка параметров компонента
     * @return mixed
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams["IBLOCK_CATALOG_ID"]) || strlen($arParams["IBLOCK_CATALOG_ID"]) <= 0) {
            $arParams["IBLOCK_CATALOG_ID"] = 0;
        }
        if (!isset($arParams["IBLOCK_CLASSIFIRE_ID"]) || strlen($arParams["IBLOCK_CLASSIFIRE_ID"]) <= 0) {
            $arParams["IBLOCK_CLASSIFIRE_ID"] = 0;
        }
        if (!isset($arParams["USER_PROPERTY_CODE"]) || strlen($arParams["USER_PROPERTY_CODE"]) <= 0) {
            $arParams["USER_PROPERTY_CODE"] = Constants::PROPERTY_FIRM_NAME;
        }
        if (!isset($arParams["ELEMENTS_COUNT"]) || strlen($arParams["ELEMENTS_COUNT"]) <= 0) {
            $arParams["ELEMENTS_COUNT"] = 0;
        }
        if (!isset($arParams["CACHE_TYPE"])) {
            $arParams["CACHE_TYPE"] = "A";
        }
        if (!isset($arParams["CACHE_TIME"])) {
            $arParams["CACHE_TIME"] = 36000000;
        }
        return $arParams;
    }

    /**
     * Обработка функционала, исполняемого компонентом
     * @return void
     */
    private function completeComponent(): void
    {
        global $USER;
        global $APPLICATION;
        global $CACHE_MANAGER;
        $cFilter = false;
        if (isset($_REQUEST["F"])) {
            $cFilter = true;
        }
        $page = 1;
        if (isset($_REQUEST["PAGEN_1"])) {
            $page = (int)htmlspecialchars($_REQUEST["PAGEN_1"]);
        }
        $this->addComponentAdminSubmenuTitle();
        if($this->StartResultCache(false, [$USER->GetGroups(), $cFilter, $page], "/servicesIblock")) {
            $CACHE_MANAGER->RegisterTag("iblock_id_3");
            $this->arResult = $this->prepareData($cFilter, $page);
            $this->SetResultCacheKeys(["SECTION_COUNT", "MAX_PRICE", "MIN_PRICE"]);
            $this->IncludeComponentTemplate();
        }
        $APPLICATION->SetTitle(GetMessage("SIMPLE_2_SECTION_COUNT_TITLE").$this->arResult["SECTION_COUNT"]);
    }

    /**
     * Добавить вёрстку кнопки ссылающуюся на админку
     * @return void
     */
    private function addComponentAdminSubmenuTitle(): void
    {
        $arButtons = CIBlock::GetPanelButtons($this->arParams["IBLOCK_CATALOG_ID"]);
        $this->AddIncludeAreaIcon(
            [
                "TITLE" => GetMessage("SIMPLE_2_SUBMENU_TITLE"),
                "URL" => $arButtons['submenu']['element_list']['ACTION_URL'],
                "IN_PARAMS_MENU" => true,
            ]
        );
    }

    /**
     * Получение и обработка данных из ИБ
     * @return array
     */
    private function prepareData(bool $cFilter, int $page): array
    {
        if ($cFilter) {
            $this->AbortResultCache();
        }
        $arResult = $this->getProductClassifire($page);
        if (!$arResult) {
            $this->AbortResultCache();
            ShowError(GetMessage("SIMPLE_2_IBLOCK_ID_NOT_FOUND"));
            return [];
        }
        $arResult = $this->setProductList($cFilter, $arResult);
        if (!$arResult) {
            $this->AbortResultCache();
            ShowError(GetMessage("SIMPLE_2_IBLOCK_ID_NOT_FOUND"));
            return [];
        }
        $arResult = $this->getMinAndMaxPrice($arResult);
        return $arResult;
    }

    /**
     * Получить классификаторы для ИБ продукция
     * @return array|bool
     */
    private function getProductClassifire(int $page): array|bool
    {
        return IBlockHelper::getProductClassifireForSimpleComp($this->arParams["IBLOCK_CLASSIFIRE_ID"],
            $this->arParams["ELEMENTS_COUNT"], $page, GetMessage("SIMPLE_2_MESS_PAGINATION"));
    }

    /**
     * Установить товары для классификаторов
     * @param array $arResult
     * @return array|bool
     */
    private function setProductList(bool $cFilter, array $arResult): array|bool
    {
        return IBlockHelper::setProductListForSimpleComp($this->arParams["IBLOCK_CATALOG_ID"], $this->arParams["USER_PROPERTY_CODE"],
            $this->arParams["TEMPLATE_DETAIL_URL"], $cFilter, $arResult);
    }

    /**
     * Установка минимальной и максимальной цены в списке товаров
     * @param array $arResult
     * @return array|bool
     */
    private function getMinAndMaxPrice(array $arResult): array|bool
    {
        return IBlockHelper::getMinAndMaxPrice($arResult);
    }
}