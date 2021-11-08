<?php
/**
 * Получает максимальную длину поля
 * @param mysqli $db Объект с базой данных
 * @param string $tableName Имя таблицы
 * @param string $columnName Имя колонки
 * @return integer Максимально возможная длина поля в колонке
 */
function getCharacterMaxLength(mysqli $db, string $tableName, string $columnName): int
{
    $sql = "
        SELECT COLUMN_NAME,
               CHARACTER_MAXIMUM_LENGTH
          FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME = ?
           AND COLUMN_NAME = ?;
    ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param(
        "ss",
        $tableName,
        $columnName
    );
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['CHARACTER_MAXIMUM_LENGTH'];
}

function getDecimalMaxLengths(mysqli $db, string $tableName, string $columnName): array
{
    $sql = "
        SELECT NUMERIC_PRECISION AS totalMaxLength,
               NUMERIC_SCALE AS decimalMaxLength
          FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME = ?
           AND COLUMN_NAME = ?;
    ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param(
        "ss",
        $tableName,
        $columnName
    );
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
