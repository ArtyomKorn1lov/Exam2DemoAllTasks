<?php

namespace lib\Exam;

use CUser;
use CAgent;
use CEvent;
use COption;
use lib\Constants;

/** Регистрация функций для агентов, обрабатывающих пользователей */
class UserAgents
{
    /**
     * Функция агента для подсчёта количества пользователей на сайте
     * @return string
     */
    public static function CheckUserCount(): string
    {
        $curDate = date('Y-m-d H:i:s');
        $sLastDate = COption::GetOptionString("main", "last_date_agent_checkUserCount");
        if (!empty($sLastDate)) {
            $arFilter = ["DATE_REGISTER_1" => $sLastDate];
        } else {
            $arFilter = [];
        }
        $users = self::getRegisterUsers($arFilter);
        if (empty($sLastDate)) {
            $sLastDate = $users[0]["DATE_REGISTER"];
        }
        $days =  (int)ceil(abs(strtotime($sLastDate) - strtotime($curDate))/86400);
        $emails = self::getAdminsEmail();
        self::sendMailsTo(count($users), $days, $emails);
        COption::SetOptionString("main", "last_date_agent_checkUserCount", $curDate);
        return Constants::CHECK_USER_COUNT_AGENT_NAME;
    }

    /**
     * Получить количество зарегистрированных пользователей после последнего выполнения агента
     * @param array $arFilter
     * @return array
     */
    private static function getRegisterUsers(array $arFilter): array
    {
        $obItems = CUser::GetList(
            "DATE_REGISTER",
            "ASC",
            $arFilter,
            ["FIELDS" => ["ID", "DATE_REGISTER"]]
        );
        $users = [];
        while ($item = $obItems->Fetch()) {
            $users[] = $item;
        }
        return $users;
    }

    /**
     * Получить Email адреса администраторов
     * @return array
     */
    private static function getAdminsEmail(): array
    {
        $obItems = CUser::GetList(
            "ID",
            "ASC",
            ["GROUPS_ID" => 1],
            ["FIELDS" => ["EMAIL"]]
        );
        $users = [];
        while ($item = $obItems->Fetch()) {
            $users[] = $item["EMAIL"];
        }
        return $users;
    }

    /**
     * Отправить письма всем администраторам
     * @param int $count
     * @param int $days
     * @param array $emails
     * @return void
     */
    private static function sendMailsTo(int $count, int $days, array $emails): void
    {
        foreach ($emails as $email) {
            CEvent::Send(Constants::MAIL_TEMPLATE_TYPE_CODE, "s1", [
                "COUNT" => $count,
                "DAYS" => $days,
                "EMAIL_TO" => $email
            ], "N");
        }
    }
}