<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/items.php';

$itemId = $_GET['item_id'] ?? null;

if (!$itemId) {
    httpError($categories, $user, 404);
}

$item = getItem($db, $itemId);

if (!$item) {
    httpError($categories, $user, 404);
}

echo getHtml('lot.php', [
    'categories' => $categories,
    'user' => $user,
    'item' => $item,
], $categories, $user, $item['item_name']);
