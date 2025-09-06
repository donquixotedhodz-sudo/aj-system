<?php
/**
 * Database Connection
 * 
 * This file establishes a connection to the MySQL database for the San Rafael Cave website.
 */

// Database configuration
$db_host = 'localhost';
$db_name = 'san_rafael_cave_db';
$db_user = 'root'; // Default XAMPP username
$db_pass = ''; // Default XAMPP password (empty)

// Create connection
$conn = null;

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Use UTF-8 encoding
    $conn->exec("SET NAMES utf8");
} catch(PDOException $e) {
    // For production, you might want to log this instead of displaying
    die("Connection failed: " . $e->getMessage());
}

// Create alias for backward compatibility
$pdo = $conn;