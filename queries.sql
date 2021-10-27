INSERT INTO categories (category_name, category_code)
VALUES ('Доски и лыжи', 'boards'),
       ('Крепления', 'attachment'),
       ('Ботинки', 'boots'),
       ('Одежда', 'clothing'),
       ('Инструменты', 'tools'),
       ('Разное', 'other');

INSERT INTO items (item_name, item_image, item_initial_price, item_bid_step, seller_id, category_id, item_date_expire)
VALUES ('2014 Rossignol District Snowboard', 'img/lot-1.jpg', 10999, 10, 1, 1, '2021-10-27'),
       ('DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', 159999, 10, 2, 1, '2021-10-28'),
       ('Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', 8000, 10, 1, 2, '2021-10-29'),
       ('Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 10999, 10, 2, 3, '2021-10-30'),
       ('Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 7500, 10, 1, 4, '2021-10-31'),
       ('Маска Oakley Canopy', 'img/lot-6.jpg', 5400, 10, 2, 6, '2021-11-01');

INSERT INTO users (user_email, user_name, user_password, user_contact_info)
VALUES ('levnevinitsin@gmail.com', 'Лев', SHA2('qwerty', 0), 'Телеграм: @LevNevinitsin'),
       ('ivanivanov@gmail.com', 'Иван', SHA2('asdf', 0), 'Телеграм: @IvanIvanov');

INSERT INTO bids (bid_price, user_id, item_id)
VALUES (12000.33, 1, 1),
       (13000.60, 2, 1),
       (15000.99, 1, 4),
       (17000.25, 2, 4);

/*
    Получить все категории
*/
SELECT category_name
  FROM categories;

/*
    Получить самые новые, открытые лоты.
    Каждый лот должен включать название, стартовую цену, ссылку на изображение,
    цену (либо текст, что ставок не было), название категории.
*/
SELECT i.item_name,
       i.item_initial_price,
       i.item_image,

       IFNULL(
          (SELECT MAX(bid_price)
            FROM bids AS b
            WHERE i.item_id = b.item_id),
          'Ставок не было'
       ) AS top_bid,

       c.category_name
  FROM items AS i
       INNER JOIN categories AS c
       ON i.category_id = c.category_id
 WHERE item_date_expire > NOW()
 ORDER BY item_date_added DESC;

/*
    Показать лот по его ID и получить название категории, к которой он принадлежит.
*/
SELECT i.item_name,
       c.category_name
  FROM items AS i
       INNER JOIN categories AS c
       ON i.category_id = c.category_id
 WHERE i.item_id = 1;

/*
    Обновить название лота по его идентификатору.
*/
UPDATE items
   SET item_name = 'my_name'
 WHERE item_id = 1;

/*
    Получить список ставок для лота по его идентификатору с сортировкой по дате.
*/
SELECT *
  FROM bids
 WHERE item_id = 1
 ORDER BY bid_date_created DESC;
