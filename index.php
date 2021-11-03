<?php
require __DIR__ . '/initialize.php';
require __DIR__ . '/models/items.php';

$newItems = $db->query(getNewItemsSql())->fetch_all(MYSQLI_ASSOC);

getHtml('main.php', ['categories' => $categories, 'items' => $newItems], $categories, $isAuth, $userName, 'Главная', true);
