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

echo getHtml('lot.php', [
    'categories' => $categories,
    'user' => $user,
    'item' => $item,
], $categories, $user, $item['item_name']);
