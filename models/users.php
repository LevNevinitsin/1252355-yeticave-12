<?php
/**
 * Добавляет в базу данных запись с новым пользователем
 * @param   mysqli   $db        Объект с базой данных
 * @param   array    $formData  Данные пользователя
 * @return  integer             id нового пользователя
 */
function insertUser(mysqli $db, array $formData): int
{
    $sql = "
        INSERT INTO users (
            user_email,
            user_name,
            user_password,
            user_contact_info
        )
        VALUES (?, ?, ?, ?)
    ";

    $params = [
        $formData['email'],
        $formData['name'],
        $formData['password'],
        $formData['message'],
    ];

    return dbProcessDml($db, $sql, $params)['insertId'];
}

/**
 * Получает данные пользователя по значению email адреса
 * @param   mysqli      $db     Объект с базой данных
 * @param   string      $email  Адрес email
 * @return  array|null          Данные пользователя либо null, если такого адреса email нет
 */
function getUserByEmail(mysqli $db, string $email): ?array
{
    $sql = "SELECT * FROM users WHERE user_email = ?";
    return dbSelectAssoc($db, $sql, [$email]);
}
