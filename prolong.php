<?php
/**
 * Устанавливает даты просроченных лотов как сегодня плюс случайное, в выбранном интервале, количество дней
 * @param  mysqli   $db   Объект с базой данных
 * @param  integer  $min  Минимальное количество дней
 * @param  integer  $max  Максимальное количество дней
 */
function prolongExpiredDates(mysqli $db, int $min = 1, int $max = 5)
{
    $sql = "
        UPDATE items
           SET item_date_expire = DATE_ADD(NOW(), INTERVAL FLOOR(RAND()*(? - ? + 1)) + ? DAY),
               winner_id = NULL
         WHERE item_date_expire < NOW()
    ";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sss", $max, $min, $min);
    $stmt->execute();
}
