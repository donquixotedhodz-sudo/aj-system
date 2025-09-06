<?php
require_once __DIR__ . '/../includes/db_connect.php';

try {
    // Add user_id column to bookings table
    $sql = "ALTER TABLE bookings 
            ADD COLUMN user_id INT(11) NULL AFTER id,
            ADD KEY user_id (user_id),
            ADD CONSTRAINT bookings_ibfk_2 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL";
    
    $pdo->exec($sql);
    echo "Successfully added user_id column to bookings table.\n";
    
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "user_id column already exists in bookings table.\n";
    } else {
        echo "Error adding user_id column: " . $e->getMessage() . "\n";
    }
}
?>