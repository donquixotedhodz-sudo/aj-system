<?php
/**
 * Authentication Check
 * 
 * This file checks if a user is logged in via session or remember me cookie.
 * Include this file at the beginning of pages that need to check authentication status.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is already logged in via session
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // User is not logged in via session, check for remember me cookie
    if (isset($_COOKIE['remember_token'])) {
        // Include database connection
        require_once 'db_connect.php';
        
        $token = $_COOKIE['remember_token'];
        $current_time = date('Y-m-d H:i:s');
        
        try {
            // Look up the token in the database
            $stmt = $conn->prepare("SELECT us.user_id, us.expires_at, u.email, u.fullname 
                                  FROM user_sessions us 
                                  JOIN users u ON us.user_id = u.id 
                                  WHERE us.session_token = :token 
                                  AND us.expires_at > :current_time");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':current_time', $current_time);
            $stmt->execute();
            
            if ($session = $stmt->fetch()) {
                // Valid token found, log the user in
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $session['user_id'];
                $_SESSION['user_email'] = $session['email'];
                $_SESSION['user_fullname'] = $session['fullname'];
                
                // Optionally refresh the token for extended usage
                $new_expires = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 days from now
                
                $update_stmt = $conn->prepare("UPDATE user_sessions SET expires_at = :expires WHERE session_token = :token");
                $update_stmt->bindParam(':expires', $new_expires);
                $update_stmt->bindParam(':token', $token);
                $update_stmt->execute();
                
                // Refresh the cookie
                setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
            } else {
                // Invalid or expired token, clear the cookie
                setcookie('remember_token', '', time() - 3600, '/', '', false, true);
            }
        } catch (PDOException $e) {
            // Log the error but don't expose it to the user
            error_log('Remember me authentication error: ' . $e->getMessage());
            // Clear the cookie in case of error
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
    }
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
}

// Function to redirect if not logged in
function require_login($redirect_url = 'login.php') {
    if (!is_logged_in()) {
        header("Location: $redirect_url");
        exit;
    }
}