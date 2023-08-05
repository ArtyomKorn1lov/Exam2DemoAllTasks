<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use CBitrixComponent;
use Bitrix\Main\Loader;
use lib\Exam\IBlockHelper;
use lib\Constants;

/**
 * Класс компонента "Каталог товаров"
 */
class SimpleComponent extends CBitrixComponent
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
            ShowError(GetMessage("SIMPLE_IBLOCK_MODULE_NOT_FOUND"));
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
        if (!isset($this->arParams["IBLOCK_NEWS_ID"]) || strlen($this->arParams["IBLOCK_NEWS_ID"]) <= 0) {
            $this->arParams["IBLOCK_NEWS_ID"] = 0;
        }
        if (!isset($this->arParams["IBLOCK_CATALOG_ID"]) || strlen($this->arParams["IBLOCK_CATALOG_ID"]) <= 0) {
            $this->arParams["IBLOCK_CATALOG_ID"] = 0;
        }
        if (!isset($this->arParams["USER_PROPERTY_CODE"]) || strlen($this->arParams["USER_PROPERTY_CODE"]) <= 0) {
            $this->arParams["USER_PROPERTY_CODE"] = Constants::USER_PROPERTY_NEWS_LINK_NAME;
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
        global $APPLICATION;
        if($this->StartResultCache()) {
            $this->arResult["ITEMS"] = $this->prepateData();
            $this->arResult["PRODUCT_COUNT"] = $this->arResult["ITEMS"]["PRODUCT_COUNT"];
            unset($this->arResult["ITEMS"]["PRODUCT_COUNT"]);
            $this->SetResultCacheKeys($this->arResult["PRODUCT_COUNT"]);
            $this->IncludeComponentTemplate();
        }
        $APPLICATION->SetTitle(GetMessage("SIMPLE_PRODUCT_COUNT_TITLE").$this->arResult["PRODUCT_COUNT"]);
    }

    /**
     * Получение и обработка данных из ИБ
     * @return array
     */
    private function prepateData(): array
    {
        $arResult = $this->getNews();
        if (!$arResult) {
            $this->AbortResultCache();
            ShowError(GetMessage("SIMPLE_IBLOCK_ID_NOT_FOUND"));
            return [];
        }
        $arResult = $this->setCatalogSectionsToResult($arResult);
        if (!$arResult) {
            $this->AbortResultCache();
            ShowError(GetMessage("SIMPLE_IBLOCK_ID_NOT_FOUND"));
            return [];
        }
        $arResult = $this->setCatalogItemsToResult($arResult);
        if (!$arResult) {
            $this->AbortResultCache();
            ShowError(GetMessage("SIMPLE_IBLOCK_ID_NOT_FOUND"));
            return [];
        }
        return $arResult;
    }

    /**
     * Получить список ИБ новости
     * @return array|bool
     */
    private function getNews(): array|bool
    {
        return IBlockHelper::getNewsForSimpleComp($this->arParams["IBLOCK_NEWS_ID"]);
    }

    /**
     * Установить в результирующий список разделы ИБ продукция, приявязанные к ИБ новости
     * @param array $arNews
     * @return array|bool
     */
    private function setCatalogSectionsToResult(array $arNews): array|bool
    {
        return IBlockHelper::setCatalogSectionsForSimpleComp($this->arParams["IBLOCK_CATALOG_ID"], $this->arParams["USER_PROPERTY_CODE"], $arNews);
    }

    /**
     * Установить в результирующий список элементы ИБ продукция, привязанные к разделам
     * @param array $arResult
     * @return array|bool
     */
    private function setCatalogItemsToResult(array $arResult): array|bool
    {
        return IBlockHelper::setCatalogItemsForSimpleComp($this->arParams["IBLOCK_CATALOG_ID"], $arResult);
    }
}