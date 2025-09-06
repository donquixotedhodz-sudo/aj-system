<?php
/**
 * Database Connection Test
 * 
 * This script tests the connection to the database and verifies that the tables exist.
 */

// Include database connection
require_once '../includes/db_connect.php';

// Set page title
$pageTitle = 'Database Connection Test';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - San Rafael Cave</title>
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
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .test-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .test-header h1 {
            color: #3a7bd5;
            font-weight: 700;
        }
        .test-result {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
        }
        .test-success {
            background-color: #d4edda;
            color: #155724;
        }
        .test-error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .test-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="test-container">
            <div class="test-header">
                <h1><?php echo $pageTitle; ?></h1>
                <p class="lead">Verifying database connection and tables</p>
            </div>
            
            <?php
            // Test database connection
            if ($conn) {
                echo '<div class="test-result test-success"><i class="fas fa-check-circle me-2"></i>Database connection successful!</div>';
                
                try {
                    // Check if users table exists
                    $stmt = $conn->prepare("SHOW TABLES LIKE 'users'");
                    $stmt->execute();
                    $usersTableExists = $stmt->rowCount() > 0;
                    
                    if ($usersTableExists) {
                        echo '<div class="test-result test-success"><i class="fas fa-check-circle me-2"></i>Users table exists!</div>';
                        
                        // Count users
                        $stmt = $conn->prepare("SELECT COUNT(*) FROM users");
                        $stmt->execute();
                        $userCount = $stmt->fetchColumn();
                        
                        echo '<div class="test-result test-info"><i class="fas fa-info-circle me-2"></i>Number of users in database: ' . $userCount . '</div>';
                    } else {
                        echo '<div class="test-result test-error"><i class="fas fa-exclamation-circle me-2"></i>Users table does not exist! Please run the database initialization script.</div>';
                    }
                    
                    // Check if user_sessions table exists
                    $stmt = $conn->prepare("SHOW TABLES LIKE 'user_sessions'");
                    $stmt->execute();
                    $sessionsTableExists = $stmt->rowCount() > 0;
                    
                    if ($sessionsTableExists) {
                        echo '<div class="test-result test-success"><i class="fas fa-check-circle me-2"></i>User sessions table exists!</div>';
                    } else {
                        echo '<div class="test-result test-error"><i class="fas fa-exclamation-circle me-2"></i>User sessions table does not exist! Please run the database initialization script.</div>';
                    }
                    
                } catch (PDOException $e) {
                    echo '<div class="test-result test-error"><i class="fas fa-exclamation-circle me-2"></i>Error checking tables: ' . $e->getMessage() . '</div>';
                }
                
            } else {
                echo '<div class="test-result test-error"><i class="fas fa-exclamation-circle me-2"></i>Database connection failed!</div>';
            }
            ?>
            
            <div class="mt-4">
                <h4>Database Configuration</h4>
                <ul class="list-group">
                    <li class="list-group-item">Host: <?php echo $db_host; ?></li>
                    <li class="list-group-item">Database: <?php echo $db_name; ?></li>
                    <li class="list-group-item">Username: <?php echo $db_user; ?></li>
                    <li class="list-group-item">Password: <em><?php echo empty($db_pass) ? '(empty)' : '(set)'; ?></em></li>
                </ul>
            </div>
            
            <div class="text-center mt-4">
                <a href="setup.php" class="btn btn-primary me-2">
                    <i class="fas fa-cogs me-2"></i>Setup Database
                </a>
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