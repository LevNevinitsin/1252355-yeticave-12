<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/items.php';

$itemId = $_GET['item_id'] ?? null;

if (!$itemId) {
    render404($categories, $isAuth, $userName);
}

$item = getItem($db, $itemId);

if (!$item) {
    render404($categories, $isAuth, $userName);
}

echo getHtml('lot.php', ['categories' => $categories, 'item' => $item], $categories, $isAuth, $userName, $item['item_name']);
