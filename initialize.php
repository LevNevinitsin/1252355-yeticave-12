<?php
session_start();

$configPath = __DIR__ . '/config.php';

if (!file_exists($configPath)) {
    $msg = 'Создайте файл config.php на основе config.sample.php и внесите туда настройки сервера MySQL';
    trigger_error($msg, E_USER_ERROR);
}

$config = require $configPath;
require __DIR__ . '/models/categories.php';
require __DIR__ . '/helpers.php';

error_reporting(E_ALL);
$isEnvLocal = $config['env_local'] ?? false;
ini_set('display_errors', $isEnvLocal);
ini_set('log_errors', !$isEnvLocal);

date_default_timezone_set($config['defaultTimezone']);
$user = $_SESSION['user'] ?? null;

$dbConfig = $config['db'];

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname'], $dbConfig['port']);
$db->set_charset($dbConfig['dbCharset']);

require __DIR__ . '/prolong.php';
prolongExpiredDates($db);

$categories = getCategories($db);

if (!filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN)) {
    set_exception_handler(function ($e) use ($categories, $user) {
        httpError($categories, $user, 500);
    });

    set_error_handler(function ($e) use ($categories, $user) {
        httpError($categories, $user, 500);
    });
}
