DROP DATABASE `fruityvice`;
CREATE DATABASE IF NOT EXISTS `fruityvice`;

use `fruityvice`;

DROP TABLE IF EXISTS `Fruits`;
DROP TABLE IF EXISTS `Nutritions`;

CREATE TABLE IF NOT EXISTS `Fruits` (
    `id` BIGINT NOT NULL,
    `name` varchar(255) NOT NULL,
    `family` varchar(255) NOT NULL,
    `genus` varchar(255) NOT NULL,
    `order` varchar(255) NOT NULL,
    `isfavorite` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `Nutritions` (
    `id` BIGINT AUTO_INCREMENT,
    `carbohydrates` DECIMAL(5, 2) NOT NULL,
    `protein` DECIMAL(5, 2) NOT NULL,
    `fat` DECIMAL(5, 2) NOT NULL,
    `calories` SMALLINT(5) NOT NULL,
    `sugar` DECIMAL(5, 2) NOT NULL,
    `fruit` BIGINT NOT NULL,
    PRIMARY KEY (`id`),
CONSTRAINT Nutritions_fruit_Fruits_id FOREIGN KEY (`fruit`) REFERENCES `Fruits`(`id`) ON DELETE CASCADE) ENGINE=InnoDB;