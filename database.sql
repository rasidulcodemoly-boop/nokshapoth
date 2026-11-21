-- -------------------------------------------------------------
-- Nokshapoth Landing Page - Orders Table Schema
-- -------------------------------------------------------------
-- cPanel phpMyAdmin-এ এই SQL কোডটি রান করলে 'orders' টেবিল তৈরি হয়ে যাবে।

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `customer_name` VARCHAR(191) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `customer_address` TEXT NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `product_price` INT(11) NOT NULL DEFAULT 0,
  `delivery_charge` INT(11) NOT NULL DEFAULT 80,
  `total_amount` INT(11) NOT NULL DEFAULT 0,
  `payment_method` VARCHAR(50) NOT NULL DEFAULT 'Cash on Delivery (COD)',
  `payment_status` VARCHAR(50) NOT NULL DEFAULT 'Pending',
  `order_status` VARCHAR(50) NOT NULL DEFAULT 'Processing',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_phone` (`customer_phone`),
  INDEX `idx_order_num` (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
