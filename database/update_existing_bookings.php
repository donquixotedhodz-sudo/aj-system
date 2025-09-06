<?php
require_once __DIR__ . '/../includes/db_connect.php';

try {
    // Update existing bookings to link them to users based on contact_email
    $sql = "UPDATE bookings b 
            JOIN users u ON b.contact_email = u.email 
            SET b.user_id = u.id 
            WHERE b.user_id IS NULL";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $updated_count = $stmt->rowCount();
    echo "Successfully updated {$updated_count} existing bookings to link them to user accounts.\n";
    
    // Show which bookings were updated
    $check_sql = "SELECT b.id, b.contact_email, u.fullname, u.email 
                  FROM bookings b 
                  JOIN users u ON b.user_id = u.id";
    
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute();
    $linked_bookings = $check_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nLinked bookings:\n";
    foreach ($linked_bookings as $booking) {
        echo "Booking ID: {$booking['id']}, User: {$booking['fullname']} ({$booking['email']})\n";
    }
    
} catch (PDOException $e) {
    echo "Error updating existing bookings: " . $e->getMessage() . "\n";
}
?>