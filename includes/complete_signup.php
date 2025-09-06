<?php
// Start session
session_start();

// Include database connection
require_once 'db_connect.php';

// Initialize response array
$response = array(
    'success' => false,
    'message' => '',
    'redirect' => ''
);

// Check if user has verified OTP and has pending signup data
if (!isset($_SESSION['pending_verification']) || $_SESSION['pending_verification']['purpose'] !== 'signup') {
    $response['message'] = 'Invalid verification session. Please start the signup process again.';
    $response['redirect'] = 'signup.php';
    echo json_encode($response);
    exit;
}

// Get pending signup data
$pending_data = $_SESSION['pending_verification'];
$fullname = $pending_data['fullname'];
$email = $pending_data['email'];
$password_hash = $pending_data['password_hash'];

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
    
    // Clear pending verification data
    unset($_SESSION['pending_verification']);
    
    $response['success'] = true;
    $response['message'] = 'Registration successful! Welcome to San Rafael Cave Resort!';
    $response['redirect'] = 'index.php';
    
} catch (PDOException $e) {
    // Registration failed
    $response['message'] = 'Sorry, there was an error creating your account. Please try again later.';
    error_log('Complete signup error: ' . $e->getMessage());
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>