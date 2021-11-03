<?php
$configPath = __DIR__ . '/config.php';

if (!file_exists($configPath)) {
    $msg = 'Создайте файл config.php на основе config.sample.php и внесите туда настройки сервера MySQL';
    trigger_error($msg, E_USER_ERROR);
}

$config = require $configPath;
require __DIR__ . '/models/categories.php';
require __DIR__ . '/helpers.php';

$isAuth = rand(0, 1);
$userName = 'Лев';

$dbCharset = 'utf8mb4';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli(...array_values($config['db']));
$db->set_charset($dbCharset);

$categories = $db->query(getCategoriesSql())->fetch_all(MYSQLI_ASSOC);
