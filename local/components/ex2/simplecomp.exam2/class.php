<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use CBitrixComponent;
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
            $this->prepareComponentParams();
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
            $this->AbortResultCache();
            ShowError(GetMessage("SIMPLE_2_IBLOCK_MODULE_NOT_FOUND"));
            return false;
        }
        return true;
    }

    /**
     * Подготовка параметров компонента
     * @return void
     */
    private function prepareComponentParams(): void
    {
        if (!isset($this->arParams["IBLOCK_CATALOG_ID"]) || strlen($this->arParams["IBLOCK_CATALOG_ID"]) <= 0) {
            $this->arParams["IBLOCK_CATALOG_ID"] = 0;
        }
        if (!isset($this->arParams["IBLOCK_CLASSIFIRE_ID"]) || strlen($this->arParams["IBLOCK_CLASSIFIRE_ID"]) <= 0) {
            $this->arParams["IBLOCK_CLASSIFIRE_ID"] = 0;
        }
        if (!isset($this->arParams["USER_PROPERTY_CODE"]) || strlen($this->arParams["USER_PROPERTY_CODE"]) <= 0) {
            $this->arParams["USER_PROPERTY_CODE"] = Constants::PROPERTY_FIRM_NAME;
        }
        if (!isset($this->arParams["CACHE_TYPE"])) {
            $this->arParams["CACHE_TYPE"] = "A";
        }
        if (!isset($this->arParams["CACHE_TIME"])) {
            $this->arParams["CACHE_TIME"] = 36000000;
        }
    }

    /**
     * Обработка функционала, исполняемого компонентом
     * @return void
     */
    private function completeComponent(): void
    {
        global $USER;
        global $APPLICATION;
        if($this->StartResultCache(false, $USER->GetGroups())) {
            $this->arResult = $this->prepareData();
            $this->SetResultCacheKeys($this->arResult["SECTION_COUNT"]);
            $this->IncludeComponentTemplate();
        }
        $APPLICATION->SetTitle(GetMessage("SIMPLE_2_SECTION_COUNT_TITLE").$this->arResult["SECTION_COUNT"]);
    }

    /**
     * Получение и обработка данных из ИБ
     * @return array
     */
    private function prepareData(): array
    {
        $arResult = $this->getProductClassifire();
        if (!$arResult) {
            $this->AbortResultCache();
            ShowError(GetMessage("SIMPLE_2_IBLOCK_ID_NOT_FOUND"));
            return [];
        }
        $arResult = $this->setProductList($arResult);
        if (!$arResult) {
            $this->AbortResultCache();
            ShowError(GetMessage("SIMPLE_2_IBLOCK_ID_NOT_FOUND"));
            return [];
        }
        return $arResult;
    }

    /**
     * Получить классификаторы для ИБ продукция
     * @return array|bool
     */
    private function getProductClassifire(): array|bool
    {
        return IBlockHelper::getProductClassifireForSimpleComp($this->arParams["IBLOCK_CLASSIFIRE_ID"]);
    }

    /**
     * Установить товары для классификаторов
     * @param array $arResult
     * @return array|bool
     */
    private function setProductList(array $arResult): array|bool
    {
        return IBlockHelper::setProductListForSimpleComp($this->arParams["IBLOCK_CATALOG_ID"], $this->arParams["USER_PROPERTY_CODE"], $this->arParams["TEMPLATE_DETAIL_URL"], $arResult);
    }
}