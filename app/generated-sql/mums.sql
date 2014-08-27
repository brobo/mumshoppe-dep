
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
    `bear_limit` INTEGER NOT NULL,
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
    `category_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `trinket_FI_1` (`category_id`),
    CONSTRAINT `trinket_FK_1`
        FOREIGN KEY (`category_id`)
        REFERENCES `trinket_category` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- letter
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `letter`;

CREATE TABLE `letter`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- accent_bow
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `accent_bow`;

CREATE TABLE `accent_bow`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `grade_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `accent_bow_FI_1` (`grade_id`),
    CONSTRAINT `accent_bow_FK_1`
        FOREIGN KEY (`grade_id`)
        REFERENCES `grade` (`id`)
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

-- ---------------------------------------------------------------------
-- bear
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `bear`;

CREATE TABLE `bear`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `senior` TINYINT(1) DEFAULT 0,
    `price` DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- mum
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mum`;

CREATE TABLE `mum`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `customer_id` INTEGER NOT NULL,
    `backing_id` INTEGER,
    `accent_bow_id` INTEGER,
    `letter1_id` INTEGER,
    `name_ribbon1` VARCHAR(255),
    `letter2_id` INTEGER,
    `name_ribbon2` VARCHAR(255),
    `status_id` INTEGER,
    `paid` TINYINT(1),
    `order_date` DATETIME,
    `paid_date` DATETIME,
    `delivery_date` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `mum_FI_1` (`customer_id`),
    INDEX `mum_FI_2` (`backing_id`),
    INDEX `mum_FI_3` (`accent_bow_id`),
    INDEX `mum_FI_4` (`letter1_id`, `letter2_id`),
    INDEX `mum_FI_5` (`status_id`),
    CONSTRAINT `mum_FK_1`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`),
    CONSTRAINT `mum_FK_2`
        FOREIGN KEY (`backing_id`)
        REFERENCES `backing` (`id`),
    CONSTRAINT `mum_FK_3`
        FOREIGN KEY (`accent_bow_id`)
        REFERENCES `accent_bow` (`id`),
    CONSTRAINT `mum_FK_4`
        FOREIGN KEY (`letter1_id`,`letter2_id`)
        REFERENCES `letter` (`id`,`id`),
    CONSTRAINT `mum_FK_5`
        FOREIGN KEY (`status_id`)
        REFERENCES `status` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- mum_trinket
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mum_trinket`;

CREATE TABLE `mum_trinket`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `mum_id` INTEGER NOT NULL,
    `trinket_id` INTEGER NOT NULL,
    `quantity` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `mum_trinket_FI_1` (`mum_id`),
    INDEX `mum_trinket_FI_2` (`trinket_id`),
    CONSTRAINT `mum_trinket_FK_1`
        FOREIGN KEY (`mum_id`)
        REFERENCES `mum` (`id`),
    CONSTRAINT `mum_trinket_FK_2`
        FOREIGN KEY (`trinket_id`)
        REFERENCES `trinket` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- mum_bear
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mum_bear`;

CREATE TABLE `mum_bear`
(
    `mum_id` INTEGER NOT NULL,
    `bear_id` INTEGER NOT NULL,
    PRIMARY KEY (`mum_id`,`bear_id`),
    INDEX `mum_bear_FI_2` (`bear_id`),
    CONSTRAINT `mum_bear_FK_1`
        FOREIGN KEY (`mum_id`)
        REFERENCES `mum` (`id`),
    CONSTRAINT `mum_bear_FK_2`
        FOREIGN KEY (`bear_id`)
        REFERENCES `bear` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- trinket_category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `trinket_category`;

CREATE TABLE `trinket_category`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- volunteer
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `volunteer`;

CREATE TABLE `volunteer`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(64),
    `password` VARCHAR(255) NOT NULL,
    `name` VARCHAR(64) NOT NULL,
    `phone` VARCHAR(16),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
