<?php
/**
 * Signup Form Handler
 * Processes and validates signup form submissions
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
    $fullname = isset($_POST['fullname']) ? trim(htmlspecialchars($_POST['fullname'])) : '';
    $email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Validate full name
    if (empty($fullname)) {
        $response['errors']['fullname'] = 'Please enter your full name';
    } elseif (strlen($fullname) < 2) {
        $response['errors']['fullname'] = 'Name must be at least 2 characters long';
    }
    
    // Validate email
    if (empty($email)) {
        $response['errors']['email'] = 'Please enter your email address';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors']['email'] = 'Please enter a valid email address';
    }
    
    // Check if the email already exists in the database
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            $response['errors']['email'] = 'This email is already registered. Please use a different email or login.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'An error occurred. Please try again later.';
        error_log('Signup email check error: ' . $e->getMessage());
    }
    
    // Validate password
    if (empty($password)) {
        $response['errors']['password'] = 'Please enter a password';
    } elseif (strlen($password) < 8) {
        $response['errors']['password'] = 'Password must be at least 8 characters long';
    }
    
    // Validate password confirmation
    if (empty($confirm_password)) {
        $response['errors']['confirm_password'] = 'Please confirm your password';
    } elseif ($password !== $confirm_password) {
        $response['errors']['confirm_password'] = 'Passwords do not match';
    }
    
    // If no validation errors, process the signup
    if (empty($response['errors'])) {
        // Hash the password for security
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)");
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hash);
            $stmt->execute();
            
            // Get the new user's ID
            $user_id = $conn->lastInsertId();
            
            // Auto-login the user after successful registration
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_fullname'] = $fullname;
            $_SESSION['user_id'] = $user_id;
            
            $response['success'] = true;
            $response['message'] = 'Registration successful! Redirecting...';
            $response['success'] = true;
            $response['message'] = 'Registration successful! Redirecting...';
        } catch (PDOException $e) {
            // Registration failed
            $response['message'] = 'Sorry, there was an error creating your account. Please try again later.';
            error_log('Signup error: ' . $e->getMessage());
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
    // Redirect based on registration status
    if ($response['success']) {
        // Set success message in session for display on index page
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Welcome! Your account has been created successfully. You are now logged in.',
            'icon' => 'fas fa-check-circle'
        ];
        // Redirect to index page after successful registration
        header('Location: ../index.php');
    } else {
        // Redirect back to the signup form with status
        $redirect_url = '../login.php';
        
        if (!empty($response['message'])) {
            $redirect_url .= '?status=error&message=' . urlencode($response['message']);
        }
        
        // Store errors in session if needed
        $_SESSION['signup_errors'] = $response['errors'];
        $_SESSION['signup_data'] = $_POST; // Store form data for repopulating the form
        
        header('Location: ' . $redirect_url);
    }
    exit;
}