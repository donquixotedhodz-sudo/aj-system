<?php
/**
 * Logout Script
 * 
 * This script handles user logout by destroying the session and clearing cookies.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection if we need to remove remember me tokens
if (isset($_COOKIE['remember_token'])) {
    require_once 'db_connect.php';
    
    // Remove the token from the database
    try {
        $token = $_COOKIE['remember_token'];
        $stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
    } catch (PDOException $e) {
        // Log the error but continue with logout
        error_log('Logout error: ' . $e->getMessage());
    }
    
    // Clear the remember me cookie
    setcookie('remember_token', '', time() - 3600, '/', '', false, true);
}

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: ../login.php');
exit;