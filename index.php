<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/items.php';

$items = getNewItems($db);

echo getHtml('main.php', ['categories' => $categories, 'items' => $items], $categories, $isAuth, $userName, 'Главная', true);
