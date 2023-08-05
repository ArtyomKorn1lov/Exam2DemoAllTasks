<?php
/** Автозагрузка классов */
Bitrix\Main\Loader::registerAutoLoadClasses(null, [
    'lib\Constants' => '/local/lib/Constants.php',
    'lib\Exam\IBlockHelper' => '/local/lib/Exam/IBlockHelper.php',
    'lib\Exam\EventLogWriter' => '/local/lib/Exam/EventLogWriter.php',
    'lib\Exam\AdminSectionHandler' => '/local/lib/Exam/AdminSectionHandler.php',
    'lib\Exam\Traits\SimpleCompManager' => '/local/lib/Exam/Traits/SimpleCompManager.php',
]);