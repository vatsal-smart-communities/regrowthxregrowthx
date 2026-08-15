-- =============================================================
-- RegrowthX RBAC Migration
-- Roles, Permissions & Role-Permission Mapping
-- =============================================================

USE `regrowthx`;

-- 1. Roles Table
CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` VARCHAR(255) DEFAULT NULL,
  `is_system` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Permissions Table
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `group_name` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Role-Permission Mapping (Many-to-Many)
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` INT NOT NULL,
  `permission_id` INT NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Add role_id column to users table (MySQL compatible)
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'regrowthx' AND TABLE_NAME = 'users' AND COLUMN_NAME = 'role_id');
SET @sql = IF(@col_exists = 0, 'ALTER TABLE `users` ADD COLUMN `role_id` INT DEFAULT NULL AFTER `role`', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add foreign key only if it doesn't exist
SET @fk_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'regrowthx' AND TABLE_NAME = 'users' AND CONSTRAINT_NAME = 'fk_users_role_id');
SET @sql_fk = IF(@fk_exists = 0, 'ALTER TABLE `users` ADD CONSTRAINT `fk_users_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE SET NULL', 'SELECT 1');
PREPARE stmt_fk FROM @sql_fk;
EXECUTE stmt_fk;
DEALLOCATE PREPARE stmt_fk;

-- =============================================================
-- SEED: Permissions
-- =============================================================
INSERT IGNORE INTO `permissions` (`name`, `slug`, `group_name`, `description`) VALUES
-- Dashboard
('View Dashboard',     'view_dashboard',   'Dashboard', 'Access the admin dashboard and view metrics'),
-- Orders
('View Orders',        'view_orders',      'Orders',    'View the orders list and order details'),
('Manage Orders',      'manage_orders',    'Orders',    'Update order status, courier, and tracking info'),
-- Products
('View Products',      'view_products',    'Products',  'View the products and variants list'),
('Manage Products',    'manage_products',  'Products',  'Add, edit, delete products and variants'),
-- Customers
('View Customers',     'view_users',       'Customers', 'View the customer list and profiles'),
('Manage Customers',   'manage_users',     'Customers', 'Ban/unban users and assign roles'),
-- Roles & Permissions
('View Roles',         'view_roles',       'Roles',     'View the roles and permissions page'),
('Manage Roles',       'manage_roles',     'Roles',     'Create, edit, and delete roles and permissions');

-- =============================================================
-- SEED: Default Roles
-- =============================================================
INSERT IGNORE INTO `roles` (`id`, `name`, `slug`, `description`, `is_system`) VALUES
(1, 'Super Admin',    'super_admin',    'Full access to all features. Cannot be deleted.', 1),
(2, 'Manager',        'manager',        'Can manage orders, products, and customers but cannot manage roles.', 0),
(3, 'Support Agent',  'support_agent',  'Can view dashboard, orders, and customers. Read-only access.', 0);

-- =============================================================
-- SEED: Role-Permission Assignments
-- =============================================================

-- Super Admin gets ALL permissions
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, id FROM `permissions`;

-- Manager gets everything except manage_roles
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 2, id FROM `permissions` WHERE slug != 'manage_roles';

-- Support Agent gets view-only permissions
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 3, id FROM `permissions` WHERE slug IN ('view_dashboard', 'view_orders', 'view_users');

-- =============================================================
-- Assign existing admin users to Super Admin role
-- =============================================================
UPDATE `users` SET `role_id` = 1 WHERE `role` = 'admin' AND `role_id` IS NULL;
