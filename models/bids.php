<?php
/**
 * Добавляет в базу данных запись с новой ставкой
 * @param  mysqli   $db       Объект с базой данных
 * @param  integer  $userId   id пользователя
 * @param  integer  $itemId   id лота
 * @param  array    $bidData  Данные о ставке
 */
function insertBid(mysqli $db, int $userId, int $itemId, array $bidData)
{
    $sql = "
        INSERT INTO bids (bid_price, user_id, item_id) VALUES
        (?, ?, ?)
    ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("sss", $bidData['cost'], $userId, $itemId);
    $stmt->execute();
}

/**
 * Получает все ставки по данному лоту
 * @param   mysqli   $db      Объект с базой данных
 * @param   integer  $itemId  id лота
 * @return  array             Ставки по лоту
 */
function getItemBids(mysqli $db, int $itemId): array
{
    $sql = "
        SELECT b.bid_price,
               b.user_id,
               b.item_id,
               b.bid_date_created,
               u.user_name
          FROM bids AS b
               INNER JOIN users AS u
               ON b.user_id = u.user_id
         WHERE b.item_id = ?
         ORDER BY bid_date_created DESC
    ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $itemId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Получает все ставки данного пользователя
 * @param   mysqli   $db      Объект с базой данных
 * @param   integer  $userId  id пользователя
 * @return  array             Ставки пользователя
 */
function getUserBids(mysqli $db, int $userId): array
{
    $sql = "
        SELECT b.bid_price,
               b.item_id,
               b.bid_date_created,
               i.item_name,
               i.item_image,
               i.item_date_expire,
               i.winner_id,
               u.user_contact_info AS seller_contact_info,
               c.category_name
          FROM bids AS b

               INNER JOIN items AS i
               ON b.item_id = i.item_id

               INNER JOIN users AS u
               ON i.seller_id = u.user_id

               INNER JOIN categories AS c
               ON i.category_id = c.category_id
         WHERE b.user_id = ?
         ORDER BY b.bid_date_created DESC
    ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
