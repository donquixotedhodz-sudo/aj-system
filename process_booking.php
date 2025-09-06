<?php
require_once 'includes/db_connect.php';
require_once 'includes/booking_helper.php';

// Start session for messages
session_start();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    $_SESSION['booking_errors'] = ['You must be logged in to make a booking.'];
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate required fields
$expedition_id = $_POST['expedition_id'] ?? '';
$tour_date = $_POST['tour_date'] ?? '';
$person1_name = trim($_POST['person1_name'] ?? '');
$person2_name = trim($_POST['person2_name'] ?? '');
$person3_name = trim($_POST['person3_name'] ?? '');
$contact_email = trim($_POST['contact_email'] ?? '');
$contact_phone = trim($_POST['contact_phone'] ?? '');

// Validation
$errors = [];

if (empty($expedition_id) || !is_numeric($expedition_id)) {
    $errors[] = 'Please select a valid expedition.';
}

if (empty($tour_date)) {
    $errors[] = 'Please select a tour date.';
} else {
    // Check if date is in the future
    $selected_date = new DateTime($tour_date);
    $today = new DateTime();
    if ($selected_date <= $today) {
        $errors[] = 'Tour date must be in the future.';
    }
}

if (empty($person1_name)) {
    $errors[] = 'At least one person name is required.';
}

if (empty($contact_email)) {
    $errors[] = 'Contact email is required.';
} elseif (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

// Validate file upload
if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
    $errors[] = 'Payment proof file is required.';
} else {
    $file = $_FILES['payment_proof'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowed_types)) {
        $errors[] = 'Payment proof must be an image (JPG, PNG, GIF) or PDF file.';
    }
    
    if ($file['size'] > $max_size) {
        $errors[] = 'Payment proof file must be less than 5MB.';
    }
}

// If there are errors, redirect back with errors
if (!empty($errors)) {
    $_SESSION['booking_errors'] = $errors;
    $_SESSION['booking_data'] = $_POST;
    header('Location: index.php?error=1');
    exit();
}

try {
    // Create uploads directory if it doesn't exist
    $upload_dir = 'uploads/payment_proofs/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate unique filename
    $file_extension = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
    $unique_filename = 'payment_' . uniqid() . '_' . time() . '.' . $file_extension;
    $upload_path = $upload_dir . $unique_filename;
    
    // Move uploaded file
    if (!move_uploaded_file($_FILES['payment_proof']['tmp_name'], $upload_path)) {
        throw new Exception('Failed to upload payment proof file.');
    }
    
    // Get additional form data
    $special_requests = $_POST['special_requests'] ?? '';
    
    // Calculate total amount (300 tour guide fee + 35 per person)
    $participant_count = 1; // At least person1
    if (!empty($person2_name)) $participant_count++;
    if (!empty($person3_name)) $participant_count++;
    $total_amount = 300 + (35 * $participant_count);
    
    // Generate professional booking reference
    $booking_reference = generateBookingReference($pdo);
    
    // Insert booking into database
    $sql = "INSERT INTO bookings (user_id, expedition_id, tour_date, person1_name, person2_name, person3_name, contact_email, contact_phone, payment_proof, total_amount, special_requests, booking_reference) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $user_id,
        $expedition_id,
        $tour_date,
        $person1_name,
        $person2_name ?: null,
        $person3_name ?: null,
        $contact_email,
        $contact_phone ?: null,
        $upload_path,
        $total_amount,
        $special_requests ?: null,
        $booking_reference
    ]);
    
    $booking_id = $pdo->lastInsertId();
    
    // Success message
    $_SESSION['booking_success'] = 'Booking submitted successfully! Your booking ID is: ' . $booking_reference;
    header('Location: customer/my_bookings.php');
    exit();
    
} catch (Exception $e) {
    // Clean up uploaded file if database insert fails
    if (isset($upload_path) && file_exists($upload_path)) {
        unlink($upload_path);
    }
    
    $_SESSION['booking_errors'] = ['An error occurred while processing your booking. Please try again.'];
    $_SESSION['booking_data'] = $_POST;
    header('Location: index.php?error=1');
    exit();
}
?>