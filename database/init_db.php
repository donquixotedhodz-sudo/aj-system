<?php
/**
 * Database Initialization Script
 * 
 * This script creates the database and tables needed for the San Rafael Cave website.
 * Run this script once to set up the database structure.
 */

// Database configuration
$db_host = 'localhost';
$db_user = 'root'; // Default XAMPP username
$db_pass = ''; // Default XAMPP password (empty)

// Create connection to MySQL without selecting a database
try {
    $conn = new PDO("mysql:host=$db_host", $db_user, $db_pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Initialization</h2>";
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS san_rafael_cave_db";
    $conn->exec($sql);
    echo "<p>Database created successfully or already exists.</p>";
    
    // Select the database
    $conn->exec("USE san_rafael_cave_db");
    
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
    echo "<p>Users table created successfully or already exists.</p>";
    
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
    echo "<p>User sessions table created successfully or already exists.</p>";
    
    // Create a demo user if none exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    $user_count = $stmt->fetchColumn();
    
    if ($user_count == 0) {
        $demo_fullname = 'Demo User';
        $demo_email = 'demo@example.com';
        $demo_password = password_hash('password123', PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)");
        $stmt->bindParam(':fullname', $demo_fullname);
        $stmt->bindParam(':email', $demo_email);
        $stmt->bindParam(':password', $demo_password);
        $stmt->execute();
        
        echo "<p>Demo user created with email: demo@example.com and password: password123</p>";
    }
    
    echo "<p>Database initialization completed successfully!</p>";
    echo "<p><a href='../index.php'>Return to homepage</a></p>";
    
} catch(PDOException $e) {
    echo "<h2>Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}

$conn = null;
?>