<?php
/**
 * Возращает список категорий
 * @param mysqli $db Объект класса mysqli
 * @return array Ассоциативный массив с данными категорий
 */
function getCategories(mysqli $db): array
{
    $sql = "SELECT category_id, category_name, category_code FROM categories";
    return dbSelectAll($db, $sql);
}
