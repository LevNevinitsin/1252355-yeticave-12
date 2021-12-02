<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/getwinner.php';

$items = getNewItems($db);
$items = includeCbResultsForEachElement($items, 'getRemainingTime', ['item_date_expire']);

echo getHtml('main.php', ['categories' => $categories, 'items' => $items], $categories, $user, 'Главная', null, true);
