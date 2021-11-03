<?php
/**
 * Возращает список категорий
 * @param object $db Объект mysqli
 * @return array Ассоциативный массив с данными категорий
 */
function getCategories(object $db): array
{
    $sql = "SELECT category_name, category_code FROM categories";
    return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
}
