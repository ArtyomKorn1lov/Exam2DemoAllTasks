<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use CBitrixComponent;
use Bitrix\Main\Loader;
use lib\Constants;
use CUser;
use lib\Exam\IBlockHelper;

/**
 * Класс компонента "Новости по интересам"
 */
class NewByInterests extends CBitrixComponent
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
            ShowError(GetMessage("INTERSTS_MODULE_IBLOCK_ERROR"));
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
        if (!isset($this->arParams["IBLOCK_PROPERTY_CODE"]) || strlen($this->arParams["IBLOCK_PROPERTY_CODE"]) <= 0) {
            $this->arParams["IBLOCK_PROPERTY_CODE"] = Constants::PROPERTY_AUTHOR_NAME;
        }
        if (!isset($this->arParams["USER_PROPERTY_CODE"]) || strlen($this->arParams["USER_PROPERTY_CODE"]) <= 0) {
            $this->arParams["USER_PROPERTY_CODE"] = Constants::USER_PROPERTY_AUTHOR_TYPE_NAME;
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
        global $USER;
        if($this->StartResultCache(false, $USER->GetID())) {
            $this->arResult = $this->prepateData($USER->GetID());
            $this->SetResultCacheKeys($this->arResult["UNIC_COUNT"]);
            $this->IncludeComponentTemplate();
        }
        $APPLICATION->SetTitle("Новостей ".$this->arResult["UNIC_COUNT"]);
    }

    /**
     * Получение и обработка данных из ИБ
     * @return array
     */
    private function prepateData(int $userId): array
    {
        if ($userId <= 0) {
            $this->AbortResultCache();
            ShowError(GetMessage("INTERSTS_USER_NOT_AUTHORIZED"));
            return [];
        }
        $result = CUser::GetByID($userId);
        $arAuthor = $result->Fetch();
        if (!$arAuthor) {
            $this->AbortResultCache();
            ShowError(GetMessage("INTERSTS_USER_TYPE_NOT_FOUND"));
            return [];
        }
        $authorType = $arAuthor[$this->arParams["USER_PROPERTY_CODE"]];
        $arResult = $this->getUsers($authorType);
        if (!$arResult) {
            $this->AbortResultCache();
            ShowError(GetMessage("INTERSTS_USER_NOT_FOUND"));
            return [];
        }
        $arResult = $this->setNewsToResult($arResult);
        if (!$arResult) {
            $this->AbortResultCache();
            ShowError(GetMessage("INTERSTS_IBLOCK_NOT_FOUND"));
            return [];
        }
        return $arResult;
    }

    /**
     * Получение списка пользователей одного типа авторов
     * @param int $authorType
     * @return array
     */
    private function getUsers(int $authorType): array|bool
    {
        return IBlockHelper::getUsers($authorType, $this->arParams["USER_PROPERTY_CODE"]);
    }

    /**
     * Установка новостей, привязанных к пользователям, в результирующий массив
     * @param array $arResult
     * @return array
     */
    private function setNewsToResult(array $arResult): array|bool
    {
        return IBlockHelper::setNewsToResult($this->arParams["IBLOCK_NEWS_ID"], $this->arParams["IBLOCK_PROPERTY_CODE"], $arResult);
    }
}