<?php
$config_path = __DIR__ . '\config.php';

if (!file_exists($config_path)) {
    $msg = 'Создайте файл config.php на основе config.sample.php и внесите туда настройки сервера MySQL';
    trigger_error($msg, E_USER_ERROR);
}

$config = require $config_path;
require __DIR__ . '\helpers.php';

$is_auth = rand(0, 1);
$user_name = 'Лев';

$db_charset = 'utf8mb4';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli(...array_values($config['db']));
$db->set_charset($db_charset);

$sql = "
    SELECT i.item_id,
           i.item_name,
           i.item_initial_price,
           i.item_image,
           i.item_date_expire,

           COALESCE(
              (SELECT MAX(b.bid_price)
                 FROM bids AS b
                WHERE i.item_id = b.item_id),
              i.item_initial_price
           ) AS current_price,

           c.category_name
      FROM items AS i
           INNER JOIN categories AS c
           ON i.category_id = c.category_id
     WHERE item_date_expire > NOW()
     ORDER BY item_date_added DESC
";

$new_items = $db->query($sql)->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT category_name, category_code FROM categories";
$categories = $db->query($sql)->fetch_all(MYSQLI_ASSOC);

$page_content = include_template('main.php', [
    'categories' => $categories,
    'items' => $new_items,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);

echo $layout_content;
