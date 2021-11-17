CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_general_ci;

USE yeticave;

CREATE TABLE categories (
    PRIMARY KEY (category_id),
    category_id   INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(50)     NOT NULL UNIQUE,
    category_code VARCHAR(50)     NOT NULL UNIQUE
);

CREATE TABLE items (
    PRIMARY KEY (item_id),
    item_id            INT(11) UNSIGNED       NOT NULL AUTO_INCREMENT,
    item_name          VARCHAR(100)           NOT NULL,
    item_description   TEXT,
    item_image         VARCHAR(100),
    item_initial_price DECIMAL(10,2) UNSIGNED NOT NULL,
    item_bid_step      DECIMAL(10,2) UNSIGNED NOT NULL,
    seller_id          INT(11) UNSIGNED       NOT NULL,
    winner_id          INT(11) UNSIGNED,
    category_id        INT(5) UNSIGNED        NOT NULL,
    item_date_expire   DATETIME               NOT NULL,
    item_date_added    DATETIME               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    /*
        На сайте будут выводиться только действуйщие лоты, следовательно часто
        будут выполняться запросы, где в WHERE будет item_date_expire.
    */
    INDEX (item_date_expire),

    -- Индексируем поле с внешним ключом
    INDEX (category_id),

    -- Добавляем внешний ключ
    CONSTRAINT fk_item_category
    FOREIGN KEY (category_id)
    REFERENCES categories(category_id)
    /*
        Удаление строки из родительской таблицы запрещено при условии наличя записей
        с соответствующим category_id в дочерней таблице.
    */
    ON DELETE RESTRICT
);

CREATE TABLE bids (
    PRIMARY KEY (bid_id),
    bid_id           INT(11) UNSIGNED       NOT NULL AUTO_INCREMENT,
    bid_price        DECIMAL(10,2) UNSIGNED NOT NULL,
    user_id          INT(11) UNSIGNED       NOT NULL,
    item_id          INT(11) UNSIGNED       NOT NULL,
    bid_date_created DATETIME               NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    PRIMARY KEY (user_id),
    user_id              INT (11) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_email           VARCHAR(50)       NOT NULL UNIQUE,
    user_name            VARCHAR(100)      NOT NULL,
    user_password        VARCHAR(255)      NOT NULL,
    user_contact_info    TEXT,
    user_date_registered DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP
);
