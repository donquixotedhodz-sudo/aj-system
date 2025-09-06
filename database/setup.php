<?php
// Start session to display messages
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - San Rafael Cave</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            padding-top: 50px;
        }
        .setup-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .setup-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .setup-header h1 {
            color: #3a7bd5;
            font-weight: 700;
        }
        .setup-step {
            margin-bottom: 25px;
            padding: 20px;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        .setup-step h3 {
            color: #3a7bd5;
            margin-bottom: 15px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #3a7bd5, #00d2ff);
            border: none;
            padding: 10px 20px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2d62a8, #00b8e0);
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="setup-container">
            <div class="setup-header">
                <h1>San Rafael Cave Database Setup</h1>
                <p class="lead">Follow the steps below to set up your database</p>
            </div>
            
            <?php if (isset($_SESSION['db_setup_message'])): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i> <?php echo htmlspecialchars($_SESSION['db_setup_message']); ?>
            </div>
            <?php
            // Clear the message after displaying
            unset($_SESSION['db_setup_message']);
            endif; ?>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> This setup will create the necessary database and tables for the San Rafael Cave website.
            </div>
            
            <div class="setup-step">
                <h3><i class="fas fa-database me-2"></i>Step 1: Database Configuration</h3>
                <p>Make sure your MySQL server is running. The default configuration uses:</p>
                <ul>
                    <li>Host: <strong>localhost</strong></li>
                    <li>Username: <strong>root</strong></li>
                    <li>Password: <strong>[empty]</strong></li>
                    <li>Database: <strong>san_rafael_cave_db</strong> (will be created)</li>
                </ul>
                <p>If you need to change these settings, edit the <code>includes/db_connect.php</code> file.</p>
            </div>
            
            <div class="setup-step">
                <h3><i class="fas fa-table me-2"></i>Step 2: Create Database & Tables</h3>
                <p>Click the button below to create the database and required tables:</p>
                <a href="init_db.php" class="btn btn-primary">
                    <i class="fas fa-cogs me-2"></i>Initialize Database
                </a>
            </div>
            
            <div class="setup-step">
                <h3><i class="fas fa-check-circle me-2"></i>Step 3: Verify Setup</h3>
                <p>After initialization, a demo user will be created with these credentials:</p>
                <ul>
                    <li>Email: <strong>demo@example.com</strong></li>
                    <li>Password: <strong>password123</strong></li>
                </ul>
                <p>You can use these credentials to test the login functionality.</p>
            </div>
            
            <div class="text-center mt-4">
                <a href="../index.php" class="btn btn-outline-primary">
                    <i class="fas fa-home me-2"></i>Return to Homepage
                </a>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>