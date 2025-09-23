CREATE TABLE `business_days` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_date` DATE NOT NULL,
  `opening_time` TIMESTAMP NOT NULL,
  `closing_time` TIMESTAMP NULL DEFAULT NULL,
  `opening_balance` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `closing_balance` DECIMAL(15,2) NOT NULL DEFAULT 0,

  -- Payment methods opening
  `opening_cash` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `opening_visa_card` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `opening_master_card` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `opening_bkash` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `opening_nagad` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `opening_rocket` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `opening_upay` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `opening_surecash` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `opening_online` DOUBLE(15,2) NOT NULL DEFAULT 0,

  -- Payment methods closing
  `closing_cash` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `closing_visa_card` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `closing_master_card` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `closing_bkash` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `closing_nagad` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `closing_rocket` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `closing_upay` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `closing_surecash` DOUBLE(15,2) NOT NULL DEFAULT 0,
  `closing_online` DOUBLE(15,2) NOT NULL DEFAULT 0,

  `status` ENUM('open','closed') NOT NULL DEFAULT 'open',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `expense_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL UNIQUE,
  `status` TINYINT NOT NULL DEFAULT 1,
  `created_by` INT NULL DEFAULT NULL,
  `updated_by` INT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `expense_articles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `expense_category_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `is_approved` TINYINT(1) NOT NULL DEFAULT 0,
  `created_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `updated_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `expenses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

  `article_id` BIGINT UNSIGNED NOT NULL,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `date` DATE NOT NULL,
  `note` TEXT NULL DEFAULT NULL,
  `pay_to` VARCHAR(255) NULL DEFAULT NULL,
  `payment_method` ENUM('cash','visa_card','master_card','bkash','nagad','rocket','upay','surecash','online') NULL DEFAULT NULL,
  `is_approved` ENUM('0','1') NOT NULL DEFAULT '0',

  `created_by` BIGINT UNSIGNED NOT NULL,
  `updated_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `business_day_id` BIGINT UNSIGNED NOT NULL,

  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `pay_to_users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `phone` VARCHAR(255) NULL DEFAULT NULL,
  `address` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



ALTER TABLE `payment_details` DROP COLUMN `payment_type`;

ALTER TABLE `payment_details` 
ADD COLUMN `payment_type` ENUM('cash','visa_card','master_card','bkash','nagad','rocket','upay','surecash','online') NOT NULL DEFAULT 'cash';


ALTER TABLE `payments`
  CHANGE COLUMN `bKash` `bkash` DOUBLE NOT NULL DEFAULT 0,
  CHANGE COLUMN `Nagad` `nagad` DOUBLE NOT NULL DEFAULT 0,
  CHANGE COLUMN `Rocket` `rocket` DOUBLE NOT NULL DEFAULT 0,
  CHANGE COLUMN `Upay` `upay` DOUBLE NOT NULL DEFAULT 0,
  CHANGE COLUMN `SureCash` `surecash` DOUBLE NOT NULL DEFAULT 0;


  -- NEXT 

  ALTER TABLE `product_sizes` ADD `fixed_price` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `discounted_price`, ADD `max_discount` INT NULL DEFAULT '0' AFTER `fixed_price`, ADD `offer_discount` INT NULL DEFAULT '0' AFTER `max_discount`, ADD `offer_from` DATE NULL AFTER `offer_discount`, ADD `offer_to` DATE NULL AFTER `offer_from`; 