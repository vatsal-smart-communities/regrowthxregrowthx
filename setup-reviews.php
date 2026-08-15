<?php
require_once __DIR__ . '/config/db.php';

try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `reviews` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `product_id` int(11) NOT NULL,
            `user_id` int(11) DEFAULT NULL,
            `reviewer_name` varchar(255) NOT NULL,
            `reviewer_email` varchar(255) NOT NULL,
            `rating` int(1) NOT NULL DEFAULT 5,
            `review_text` text NOT NULL,
            `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `product_id` (`product_id`),
            KEY `user_id` (`user_id`),
            CONSTRAINT `fk_review_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Insert dummy approved reviews for product 1
    $pdo->exec("
        INSERT INTO `reviews` (`product_id`, `reviewer_name`, `reviewer_email`, `rating`, `review_text`, `status`, `created_at`) VALUES
        (1, 'Robert T.', 'robert@example.com', 5, 'Seeing noticeable vertex regrowth after 10 weeks of daily application!', 'approved', DATE_SUB(NOW(), INTERVAL 14 DAY)),
        (1, 'Marcus V.', 'marcus@example.com', 5, 'Great non-greasy solution. Highly recommended!', 'approved', DATE_SUB(NOW(), INTERVAL 7 DAY));
    ");
    
    echo "Reviews table created and populated.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
