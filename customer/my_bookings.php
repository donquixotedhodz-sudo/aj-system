<?php
require_once '../includes/db_connect.php';
require_once '../includes/booking_helper.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Check for success message
$success_message = '';
if (isset($_SESSION['booking_success'])) {
    $success_message = $_SESSION['booking_success'];
    unset($_SESSION['booking_success']);
}

// Fetch user's bookings
try {
    $stmt = $pdo->prepare("
        SELECT b.*, e.name as expedition_title, e.price as expedition_price 
        FROM bookings b 
        JOIN cave_explorations e ON b.expedition_id = e.id 
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching bookings: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - San Rafael Cave</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Navbar styles from main site */
        .navbar {
            background-color: rgba(0, 0, 0, 0.9);
            transition: all 0.3s ease;
            padding: 0.7rem 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            color: #fff;
        }

        .nav-link {
            font-weight: 500;
            margin: 0 0.5rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #3498db, #6c5ce7);
            bottom: -3px;
            left: 0;
            transition: width 0.3s ease;
        }

        .nav-link:hover:after,
        .nav-link.active:after {
            width: 100%;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            padding-top: 76px;
        }
        
        .table-container {
            margin-top: 20px;
        }
        
        /* Custom hover effect for Back to Home button */
        .navbar .nav-link {
            position: relative;
            text-decoration: none;
        }
        
        .navbar .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #3498db, #6c5ce7);
            bottom: -3px;
            left: 0;
            transition: width 0.3s ease;
        }
        
        .navbar .nav-link:hover:after {
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php">San Rafael Cave</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home me-2"></i>
                            <span>Back to Home</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">My Bookings</h2>
                
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($success_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if (empty($bookings)): ?>
                    <div class="alert alert-info">
                        <h5>No bookings found</h5>
                        <p>You haven't made any bookings yet. <a href="../index.php" class="alert-link">Browse our expeditions</a> to make your first booking!</p>
                    </div>
                <?php else: ?>
                    <div class="table-container" style="position: relative; max-height: 60vh; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem;">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th class="text-center">Booking ID</th>
                                    <th class="text-center">Expedition</th>
                                    <th class="text-center">Tour Date</th>
                                    <th class="text-center">Participants</th>
                                    <th class="text-center">Total Amount</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td class="text-center"><?php echo htmlspecialchars(formatBookingReference($booking['booking_reference'], $booking['id'])); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($booking['expedition_title']); ?></td>
                                        <td class="text-center"><?php echo date('M d, Y', strtotime($booking['tour_date'])); ?></td>
                                        <td class="text-center">
                                            <?php 
                                            $participant_count = 0;
                                            if (!empty($booking['person1_name'])) $participant_count++;
                                            if (!empty($booking['person2_name'])) $participant_count++;
                                            if (!empty($booking['person3_name'])) $participant_count++;
                                            ?>
                                            <span class="badge bg-info text-dark fs-6"><?php echo $participant_count; ?> person<?php echo $participant_count > 1 ? 's' : ''; ?></span>
                                        </td>
                                        <td class="text-center">$<?php echo number_format($booking['total_amount'], 2); ?></td>
                                        <td class="text-center">
                                            <?php 
                                            $status = $booking['booking_status'];
                                            $badgeClass = '';
                                            switch($status) {
                                                case 'confirmed':
                                                    $badgeClass = 'bg-success';
                                                    break;
                                                case 'pending':
                                                    $badgeClass = 'bg-warning text-dark';
                                                    break;
                                                case 'cancelled':
                                                    $badgeClass = 'bg-danger';
                                                    break;
                                                default:
                                                    $badgeClass = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($status); ?></span>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group-vertical btn-group-sm">
                                                <?php if (!empty($booking['payment_proof'])): ?>
                                                    <a href="../uploads/payment_proofs/<?php echo htmlspecialchars($booking['payment_proof']); ?>" 
                                                       class="btn btn-outline-primary btn-sm" target="_blank">View Payment</a>
                                                <?php endif; ?>
                                                <?php if (!empty($booking['special_requests'])): ?>
                                                    <button class="btn btn-outline-info btn-sm" 
                                                            data-bs-toggle="tooltip" 
                                                            title="<?php echo htmlspecialchars($booking['special_requests']); ?>">Notes</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>

    <style>
        .table-container {
            overflow-x: auto;
        }
        
        .table-container table {
            table-layout: auto;
            min-width: 100%;
        }
        
        .table-container .table td,
        .table-container .table th {
            white-space: normal;
            word-wrap: break-word;
            padding: 0.75rem 0.5rem;
            vertical-align: top;
        }
        
        /* Minimum widths for better readability */
        .table-container th:nth-child(1),
        .table-container td:nth-child(1) { min-width: 120px; }
        .table-container th:nth-child(2),
        .table-container td:nth-child(2) { min-width: 150px; }
        .table-container th:nth-child(3),
        .table-container td:nth-child(3) { min-width: 100px; }
        .table-container th:nth-child(4),
        .table-container td:nth-child(4) { min-width: 180px; }
        .table-container th:nth-child(5),
        .table-container td:nth-child(5) { min-width: 80px; }
        .table-container th:nth-child(6),
        .table-container td:nth-child(6) { min-width: 80px; }
        .table-container th:nth-child(7),
        .table-container td:nth-child(7) { min-width: 140px; }
        .table-container th:nth-child(8),
        .table-container td:nth-child(8) { min-width: 100px; }
        .table-container th:nth-child(9),
        .table-container td:nth-child(9) { min-width: 120px; }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table-container {
                font-size: 0.875rem;
            }
            
            .table-container .table td,
            .table-container .table th {
                padding: 0.5rem 0.25rem;
            }
        }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>