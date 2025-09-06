<?php
require_once __DIR__ . '/../includes/db_connect.php';

try {
    // Add booking_reference column to bookings table
    $sql = "ALTER TABLE bookings 
            ADD COLUMN booking_reference VARCHAR(50) NULL UNIQUE AFTER id";
    
    $pdo->exec($sql);
    echo "Successfully added booking_reference column to bookings table.\n";
    
    // Create index for better performance
    $index_sql = "ALTER TABLE bookings ADD INDEX idx_booking_reference (booking_reference)";
    $pdo->exec($index_sql);
    echo "Successfully added index for booking_reference column.\n";
    
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "booking_reference column already exists in bookings table.\n";
    } else {
        echo "Error adding booking_reference column: " . $e->getMessage() . "\n";
    }
}
?>