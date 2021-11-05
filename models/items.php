<?php
/**
 * Возвращает лот по его id
 * @param mysqli $db Объект класса mysqli
 * @param integer $itemId id лота
 * @return array|null Ассоциативный массив с данными лота
 */

function getItem(mysqli $db, int $itemId): ?array
{
    $sql = "
        SELECT i.item_name,
               i.item_description,
               i.item_initial_price,
               i.item_bid_step,
               i.item_image,
               i.item_date_expire,

               COALESCE(
                  (SELECT MAX(b.bid_price)
                     FROM bids AS b
                    WHERE i.item_id = b.item_id),
                  i.item_initial_price
               ) AS current_price,

               c.category_name
          FROM items AS i
               INNER JOIN categories AS c
               ON i.category_id = c.category_id
         WHERE i.item_id = ?
    ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $itemId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Возвращает дейстующие лоты, отсортированные от новых к старым
 *
 * @param mysqli $db Объект класса mysqli
 * @return array|null Ассоциативный массив с данными лотов
 */
function getNewItems(mysqli $db): ?array
{
    $sql = "
        SELECT i.item_id,
               i.item_name,
               i.item_initial_price,
               i.item_image,
               i.item_date_expire,

               COALESCE(
                  (SELECT MAX(b.bid_price)
                     FROM bids AS b
                    WHERE i.item_id = b.item_id),
                  i.item_initial_price
               ) AS current_price,

               c.category_name
          FROM items AS i
               INNER JOIN categories AS c
               ON i.category_id = c.category_id
         WHERE item_date_expire > NOW()
         ORDER BY item_date_added DESC
    ";
    return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function insertItem(mysqli $db, string $imagePath)
{
    $sql = "
        INSERT INTO items (
            item_name,
            item_description,
            item_image,
            item_initial_price,
            item_bid_step,
            seller_id,
            category_id,
            item_date_expire
        )
        VALUES (?, ?, ?, ?, ?, 1, ?, ?)
    ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param(
        "sssssss",
        $_POST['lot-name'],
        $_POST['description'],
        $imagePath,
        $_POST['lot-rate'],
        $_POST['lot-step'],
        $_POST['category_id'],
        $_POST['lot-date']
    );
    $stmt->execute();
}
