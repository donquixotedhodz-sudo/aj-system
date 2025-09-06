<?php
/**
 * Script to add profile_picture column to users table
 */

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'san_rafael_cave_db';

try {
    // Create connection
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if column already exists
    $stmt = $conn->prepare("SHOW COLUMNS FROM users LIKE 'profile_picture'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "Profile picture column already exists.\n";
    } else {
        // Add the profile_picture column
        $sql = "ALTER TABLE users ADD COLUMN profile_picture VARCHAR(500) DEFAULT NULL AFTER password";
        $conn->exec($sql);
        echo "Profile picture column added successfully!\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

$conn = null;
?>