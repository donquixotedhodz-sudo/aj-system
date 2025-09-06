<?php
/**
 * Contact Form Handler
 * Processes and validates contact form submissions
 */

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize inputs
    $name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : '';
    $email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
    $phone = isset($_POST['phone']) ? trim(htmlspecialchars($_POST['phone'])) : '';
    $expedition = isset($_POST['expedition']) ? trim(htmlspecialchars($_POST['expedition'])) : '';
    $message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'])) : '';
    
    // Validate name
    if (empty($name)) {
        $response['errors']['name'] = 'Please enter your name';
    } elseif (strlen($name) < 2) {
        $response['errors']['name'] = 'Name must be at least 2 characters long';
    }
    
    // Validate email
    if (empty($email)) {
        $response['errors']['email'] = 'Please enter your email address';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors']['email'] = 'Please enter a valid email address';
    }
    
    // Validate phone (optional)
    if (!empty($phone) && !preg_match('/^[0-9\-\(\)\s\+\.]+$/', $phone)) {
        $response['errors']['phone'] = 'Please enter a valid phone number';
    }
    
    // Validate message
    if (empty($message)) {
        $response['errors']['message'] = 'Please enter your message';
    } elseif (strlen($message) < 10) {
        $response['errors']['message'] = 'Message must be at least 10 characters long';
    }
    
    // If no validation errors, process the form
    if (empty($response['errors'])) {
        // Prepare email content
        $to = 'info@cavexplore.com'; // Replace with your actual email
        $subject = 'New Contact Form Submission from CaveXplore';
        
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n";
        
        if (!empty($phone)) {
            $email_content .= "Phone: $phone\n";
        }
        
        if (!empty($expedition)) {
            $email_content .= "Interested Expedition: $expedition\n";
        }
        
        $email_content .= "Message:\n$message\n";
        
        // Email headers
        $headers = "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        
        // For demonstration purposes, we'll just simulate a successful email sending
        // In a production environment, you would use mail() or a library like PHPMailer
        
        // Simulate successful email sending
        $email_sent = true;
        
        if ($email_sent) {
            // Success response
            $response['success'] = true;
            $response['message'] = 'Thank you for your message! We will get back to you soon.';
            
            // Log the submission (optional)
            $log_file = __DIR__ . '/contact_submissions.log';
            $log_entry = date('Y-m-d H:i:s') . " - Name: $name, Email: $email, Expedition: $expedition\n";
            
            // Uncomment to enable logging
            // file_put_contents($log_file, $log_entry, FILE_APPEND);
            
            // Store in database (optional)
            // This would be implemented if you have a database connection
        } else {
            // Email sending failed
            $response['message'] = 'Sorry, there was an error sending your message. Please try again later.';
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
    // Redirect back to the contact form with status
    $redirect_url = '../index.php#contact';
    
    if ($response['success']) {
        $redirect_url .= '?status=success&message=' . urlencode($response['message']);
    } else {
        $redirect_url .= '?status=error&message=' . urlencode($response['message']);
        
        // Add errors to session if needed
        session_start();
        $_SESSION['form_errors'] = $response['errors'];
        $_SESSION['form_data'] = $_POST; // Store form data for repopulating the form
    }
    
    header('Location: ' . $redirect_url);
    exit;
}