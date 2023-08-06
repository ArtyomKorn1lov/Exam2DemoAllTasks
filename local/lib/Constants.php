<?php

namespace lib;

/**
 * Глобальные константы
 */
class Constants
{
    /** @var string символьный код ИБ Продукция */
    public const IBLOCK_CODE_PRODUCTS = "furniture_products_s1";

    /** @var string наименование события FEEDBACK_FORM */
    public const FEEDBACK_FORM_EVENT_NAME = "FEEDBACK_FORM";

    /** @var string символьный код группы контент-редакторы */
    public const CONTENT_EDITOR_NAME = "content_editor";

    /** @var string символьный код ИБ Метатеги */
    public const IBLOCK_CODE_METATAGS = "metatags";

    /** @var string пользовательское свойство разделов ИБ продукция для связи с новостями */
    public const USER_PROPERTY_NEWS_LINK_NAME = "UF_NEWS_LINK";

    /** @var string свойство ИБ продукция для привязки фирм */
    public const PROPERTY_FIRM_NAME = "FIRM";
}