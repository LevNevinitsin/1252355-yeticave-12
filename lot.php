<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/items.php';

$itemId = $_GET['item_id'] ?? NULL;
$item = NULL;

if ($itemId) {
    $stmt = $db->prepare(getItemSql());
    $stmt->bind_param("s", $itemId);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
}

if ($item) {
    getHtml('lot.php', ['categories' => $categories, 'item' => $item], $categories, $isAuth, $userName, $item['item_name']);
} else {
    getHtml('404.php', ['categories' => $categories], $categories, $isAuth, $userName, 'Страница не найдена');
}
