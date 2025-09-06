<?php
/**
 * Database Check
 * 
 * This file checks if the database exists and is properly set up.
 * If not, it redirects to the database setup page.
 */

// Function to check if the database is set up
function is_database_setup() {
    // Database configuration
    $db_host = 'localhost';
    $db_name = 'san_rafael_cave_db';
    $db_user = 'root'; // Default XAMPP username
    $db_pass = ''; // Default XAMPP password (empty)
    
    try {
        // Try to connect to the database
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check if users table exists
        $stmt = $conn->prepare("SHOW TABLES LIKE 'users'");
        $stmt->execute();
        $usersTableExists = $stmt->rowCount() > 0;
        
        // Check if user_sessions table exists
        $stmt = $conn->prepare("SHOW TABLES LIKE 'user_sessions'");
        $stmt->execute();
        $sessionsTableExists = $stmt->rowCount() > 0;
        
        // Return true if both tables exist
        return ($usersTableExists && $sessionsTableExists);
        
    } catch(PDOException $e) {
        // If there's an error (database doesn't exist or connection failed), return false
        return false;
    }
}

// Check if the database is set up and redirect if not
function check_database_or_redirect() {
    // Skip redirect for database setup pages
    $current_script = basename($_SERVER['SCRIPT_NAME']);
    $setup_pages = ['setup.php', 'init_db.php', 'test_connection.php', 'init_db_cli.php'];
    
    // If we're already on a setup page, don't redirect
    if (in_array($current_script, $setup_pages) || strpos($_SERVER['SCRIPT_NAME'], '/database/') !== false) {
        return;
    }
    
    // Check if database is set up
    if (!is_database_setup()) {
        // Set a session message
        session_start();
        $_SESSION['db_setup_message'] = 'The database has not been set up yet. Please complete the setup process.';
        
        // Redirect to setup page
        header('Location: ' . get_base_url() . 'database/setup.php');
        exit;
    }
}

// Helper function to get the base URL
function get_base_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    $base_dir = dirname(dirname($script_name));
    $base_url = $protocol . $host . $base_dir;
    if (substr($base_url, -1) !== '/') {
        $base_url .= '/';
    }
    return $base_url;
}

// Run the check
check_database_or_redirect();
?>