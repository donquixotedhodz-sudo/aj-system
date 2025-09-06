<?php
/**
 * Create Bookings Table
 * 
 * This script creates the bookings table in the existing database.
 */

// Include database connection
require_once __DIR__ . '/../includes/db_connect.php';

try {
    echo "<h2>Creating Bookings Table</h2>";
    
    // Create bookings table
    $sql = "CREATE TABLE IF NOT EXISTS `bookings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `expedition_id` int(11) NOT NULL,
        `tour_date` date NOT NULL,
        `person1_name` varchar(100) NOT NULL,
        `person2_name` varchar(100) DEFAULT NULL,
        `person3_name` varchar(100) DEFAULT NULL,
        `contact_email` varchar(100) NOT NULL,
        `contact_phone` varchar(20) DEFAULT NULL,
        `payment_proof` varchar(500) DEFAULT NULL,
        `total_amount` decimal(10,2) NOT NULL,
        `booking_status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
        `special_requests` text DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `expedition_id` (`expedition_id`),
        CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`expedition_id`) REFERENCES `cave_explorations` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($sql);
    echo "<p><strong>Bookings table created successfully!</strong></p>";
    
    // Create uploads directory if it doesn't exist
    $uploadsDir = __DIR__ . '/../uploads';
    if (!file_exists($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
        echo "<p>Uploads directory created successfully!</p>";
    }
    
    echo "<p><a href='../index.php'>Back to Website</a></p>";
    
} catch(PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>