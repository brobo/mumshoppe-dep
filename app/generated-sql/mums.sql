
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- grade
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `grade`;

CREATE TABLE `grade`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(32) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- product
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(32) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- size
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `size`;

CREATE TABLE `size`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(32) NOT NULL,
    `product_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `size_FI_1` (`product_id`),
    CONSTRAINT `size_FK_1`
        FOREIGN KEY (`product_id`)
        REFERENCES `product` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- backing
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `backing`;

CREATE TABLE `backing`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(32) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `size_id` INTEGER NOT NULL,
    `grade_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `backing_FI_1` (`size_id`),
    INDEX `backing_FI_2` (`grade_id`),
    CONSTRAINT `backing_FK_1`
        FOREIGN KEY (`size_id`)
        REFERENCES `size` (`id`),
    CONSTRAINT `backing_FK_2`
        FOREIGN KEY (`grade_id`)
        REFERENCES `grade` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- customer
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(64) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `name` VARCHAR(64) NOT NULL,
    `phone` VARCHAR(16) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- trinket
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `trinket`;

CREATE TABLE `trinket`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL,
    `underclassman` TINYINT(1) DEFAULT 0 NOT NULL,
    `junior` TINYINT(1) DEFAULT 0 NOT NULL,
    `senior` TINYINT(1) DEFAULT 0 NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- status
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `status`;

CREATE TABLE `status`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
