<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/items.php';

$items = getNewItems($db);
foreach ($items as &$item) {
    list (
        $item['remainingHours'],
        $item['remainingMinutes'],
        $item['remainingSeconds']
    ) = getRemainingTime($item['item_date_expire']);
}

echo getHtml('main.php', ['categories' => $categories, 'items' => $items], $categories, $user, 'Главная', null, true);
