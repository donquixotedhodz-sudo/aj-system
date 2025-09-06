<?php
/**
 * Command Line Database Initialization Script
 * 
 * This script creates the database and tables needed for the San Rafael Cave website.
 * It can be run from the command line using: php init_db_cli.php
 */

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    echo "This script is meant to be run from the command line.\n";
    echo "Usage: php init_db_cli.php\n";
    exit(1);
}

echo "=== San Rafael Cave Database Initialization ===\n\n";

// Database configuration
$db_host = 'localhost';
$db_user = 'root'; // Default XAMPP username
$db_pass = ''; // Default XAMPP password (empty)

// Create connection to MySQL without selecting a database
try {
    echo "Connecting to MySQL...\n";
    $conn = new PDO("mysql:host=$db_host", $db_user, $db_pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Creating database...\n";
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS san_rafael_cave_db";
    $conn->exec($sql);
    echo "Database created successfully or already exists.\n";
    
    // Select the database
    $conn->exec("USE san_rafael_cave_db");
    
    echo "Creating users table...\n";
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT(11) NOT NULL AUTO_INCREMENT,
        fullname VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL,
        profile_picture VARCHAR(500) DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($sql);
    echo "Users table created successfully or already exists.\n";
    
    echo "Creating user_sessions table...\n";
    // Create user_sessions table
    $sql = "CREATE TABLE IF NOT EXISTS user_sessions (
        id INT(11) NOT NULL AUTO_INCREMENT,
        user_id INT(11) NOT NULL,
        session_token VARCHAR(255) NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id),
        CONSTRAINT user_sessions_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($sql);
    echo "User sessions table created successfully or already exists.\n";
    
    // Create a demo user if none exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    $user_count = $stmt->fetchColumn();
    
    if ($user_count == 0) {
        echo "Creating demo user...\n";
        $demo_fullname = 'Demo User';
        $demo_email = 'demo@example.com';
        $demo_password = password_hash('password123', PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)");
        $stmt->bindParam(':fullname', $demo_fullname);
        $stmt->bindParam(':email', $demo_email);
        $stmt->bindParam(':password', $demo_password);
        $stmt->execute();
        
        echo "Demo user created with email: demo@example.com and password: password123\n";
    } else {
        echo "Demo user already exists. Skipping creation.\n";
    }
    
    echo "\nDatabase initialization completed successfully!\n";
    
} catch(PDOException $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    exit(1);
}

$conn = null;
echo "\nYou can now access the website and log in with the demo account.\n";
?>