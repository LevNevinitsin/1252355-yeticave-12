<?php
$configPath = __DIR__ . '/config.php';

if (!file_exists($configPath)) {
    $msg = 'Создайте файл config.php на основе config.sample.php и внесите туда настройки сервера MySQL';
    trigger_error($msg, E_USER_ERROR);
}

$config = require $configPath;
require __DIR__ . '/models/categories.php';
require __DIR__ . '/helpers.php';

date_default_timezone_set('Europe/Moscow');
$isAuth = rand(0, 1);
$userName = 'Лев';

$dbConfig = $config['db'];

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname'], $dbConfig['port']);
$db->set_charset($dbConfig['dbCharset']);

$categories = getCategories($db);
