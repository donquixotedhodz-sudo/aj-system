<?php
/**
 * Login Form Handler
 * Processes and validates login form submissions
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once 'db_connect.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize inputs
    $email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $remember = isset($_POST['remember']) ? true : false;
    
    // Validate email
    if (empty($email)) {
        $response['errors']['email'] = 'Please enter your email address';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors']['email'] = 'Please enter a valid email address';
    }
    
    // Validate password
    if (empty($password)) {
        $response['errors']['password'] = 'Please enter your password';
    }
    
    // If no validation errors, process the login
    if (empty($response['errors'])) {
        // Verify credentials against the database
        try {
            $stmt = $conn->prepare("SELECT id, fullname, email, password FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($user = $stmt->fetch()) {
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Successful login
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_fullname'] = $user['fullname'];
                    $_SESSION['user_id'] = $user['id'];
            
            // Set remember me cookie if requested
            if ($remember) {
                $token = bin2hex(random_bytes(32)); // Generate a secure token
                $expires = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 days from now
                
                // Store token in database
                $stmt = $conn->prepare("INSERT INTO user_sessions (user_id, session_token, expires_at) VALUES (:user_id, :token, :expires)");
                $stmt->bindParam(':user_id', $user['id']);
                $stmt->bindParam(':token', $token);
                $stmt->bindParam(':expires', $expires);
                $stmt->execute();
                
                // Set cookie to expire in 30 days
                setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
            }
            
            $response['success'] = true;
            $response['message'] = 'Login successful! Redirecting...';
                } else {
                    // Invalid password
                    $response['message'] = 'Invalid email or password. Please try again.';
                }
            } else {
                // User not found
                $response['message'] = 'Invalid email or password. Please try again.';
            }
        } catch (PDOException $e) {
            // Database error
            $response['message'] = 'An error occurred. Please try again later.';
            // Log the error (in a production environment)
            error_log('Login error: ' . $e->getMessage());
        }
    } else {
        // Validation errors
        $response['message'] = 'Please correct the errors in the form.';
    }
} else {
    // Not a POST request
    $response['message'] = 'Invalid request method.';
}

// Return JSON response for AJAX requests
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    // Redirect based on login status
    if ($response['success']) {
        // Redirect to dashboard or home page after successful login
        header('Location: ../index.php');
    } else {
        // Redirect back to the login form with status
        $redirect_url = '../login.php';
        
        if (!empty($response['message'])) {
            $redirect_url .= '?status=error&message=' . urlencode($response['message']);
        }
        
        // Store errors in session if needed
        $_SESSION['login_errors'] = $response['errors'];
        $_SESSION['login_data'] = $_POST; // Store form data for repopulating the form
        
        header('Location: ' . $redirect_url);
    }
    exit;
}