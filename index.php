<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/items.php';

echo getHtml('main.php', ['categories' => $categories, 'items' => getNewItems($db)], $categories, $isAuth, $userName, 'Главная', true);
