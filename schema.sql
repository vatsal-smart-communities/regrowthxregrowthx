-- RegrowthX Production MySQL Schema (Priority 1)
-- Database: regrowthx

CREATE DATABASE IF NOT EXISTS `regrowthx` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `regrowthx`;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(120) DEFAULT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `phone` VARCHAR(20) DEFAULT NULL,
  `role` ENUM('customer', 'admin') DEFAULT 'customer',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Email OTPs Table
CREATE TABLE IF NOT EXISTS `email_otps` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(150) NOT NULL,
  `otp_code` VARCHAR(10) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `is_used` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_email_otp` (`email`, `otp_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Products Table
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(200) NOT NULL UNIQUE,
  `description` TEXT,
  `active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Product Variants Table (60mL & 360mL Packs)
CREATE TABLE IF NOT EXISTS `product_variants` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `variant_key` VARCHAR(50) NOT NULL UNIQUE, -- e.g. '60ml', '360ml'
  `variant_name` VARCHAR(100) NOT NULL, -- e.g. '60 mL (1 Month Supply)'
  `price_inr` DECIMAL(10,2) NOT NULL,
  `mrp_inr` DECIMAL(10,2) NOT NULL,
  `stock_qty` INT DEFAULT 100,
  `image_path` VARCHAR(255) DEFAULT 'img/product-box-bottle.jpg',
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Orders Table
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(50) NOT NULL UNIQUE, -- e.g. RGX-IN-1001
  `user_id` INT DEFAULT NULL,
  `customer_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address_line` TEXT NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `pincode` VARCHAR(15) NOT NULL,
  `landmark` VARCHAR(150) DEFAULT NULL,
  `subtotal_amount` DECIMAL(10,2) NOT NULL,
  `shipping_amount` DECIMAL(10,2) DEFAULT 0.00,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `payment_method` ENUM('COD', 'Prepaid') DEFAULT 'COD',
  `payment_status` ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
  `order_status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
  `courier_name` VARCHAR(100) DEFAULT NULL,
  `tracking_id` VARCHAR(100) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Order Items Table
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `variant_id` INT NOT NULL,
  `item_name` VARCHAR(150) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`variant_id`) REFERENCES `product_variants`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED DATA: Default Product & Variants
INSERT IGNORE INTO `products` (`id`, `title`, `slug`, `description`, `active`) VALUES
(1, 'RegrowthX 5% Minoxidil Hair Serum', 'regrowthx-5-minoxidil-hair-serum', 'Extra Strength 5% Minoxidil Topical Solution USP for Men. Clinically proven hair regrowth treatment.', 1);

INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `variant_key`, `variant_name`, `price_inr`, `mrp_inr`, `stock_qty`, `image_path`) VALUES
(1, 1, '60ml', '60 mL (1 Month Supply)', 19.99, 34.99, 250, 'img/product-box-bottle.jpg'),
(2, 1, '360ml', '360 mL (6 Month Bundle)', 79.99, 129.99, 100, 'img/timeline-results.jpg');

-- SEED DATA: Default Admin User
INSERT IGNORE INTO `users` (`id`, `full_name`, `email`, `phone`, `role`, `status`) VALUES
(1, 'Nimex Group Admin', 'rickw@nimexgrp.com', '7184387400', 'admin', 'active');
