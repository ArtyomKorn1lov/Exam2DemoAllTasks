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

    /** @var string свойство ИБ новости для привязки к пользователям */
    public const PROPERTY_AUTHOR_NAME = "AUTHOR";

    /** @var string пользовательское свойство для типов авторов к пользователям */
    public const USER_PROPERTY_AUTHOR_TYPE_NAME = "UF_AUTHOR_TYPE";

    /** @var string функция, которую выполнят агент */
    public const CHECK_USER_COUNT_AGENT_NAME = "lib\Exam\UserAgents::CheckUserCount();";

    /** @var string символьный код почтового шаблона Подсчёт пользователей */
    public const MAIL_TEMPLATE_TYPE_CODE = "USER_COUNT";

    /** @var string символьный код ИБ Жалобы на новости */
    public const IBLOCK_CODE_COMPLAINS = "complaints-about-the-news";
}