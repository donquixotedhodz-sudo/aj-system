<?php
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/booking_helper.php';

try {
    // Get all bookings that don't have a booking_reference yet
    $stmt = $pdo->prepare("SELECT id, created_at FROM bookings WHERE booking_reference IS NULL ORDER BY created_at ASC");
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $updated_count = 0;
    
    foreach ($bookings as $booking) {
        // Extract year and month from the booking's creation date
        $created_date = new DateTime($booking['created_at']);
        $year_month = $created_date->format('Ym');
        
        // Get the next sequential number for this month based on existing references
        $count_sql = "SELECT COUNT(*) + 1 as next_number 
                      FROM bookings 
                      WHERE booking_reference LIKE 'SRC-" . $year_month . "-%'";
        
        $count_stmt = $pdo->prepare($count_sql);
        $count_stmt->execute();
        $count_result = $count_stmt->fetch(PDO::FETCH_ASSOC);
        
        $next_number = $count_result['next_number'];
        $formatted_number = str_pad($next_number, 4, '0', STR_PAD_LEFT);
        $booking_reference = "SRC-{$year_month}-{$formatted_number}";
        
        // Update the booking with the new reference
        $update_sql = "UPDATE bookings SET booking_reference = ? WHERE id = ?";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([$booking_reference, $booking['id']]);
        
        $updated_count++;
        echo "Updated booking ID {$booking['id']} with reference: {$booking_reference}\n";
    }
    
    echo "\nSuccessfully updated {$updated_count} existing bookings with professional booking references.\n";
    
} catch (PDOException $e) {
    echo "Error updating existing booking references: " . $e->getMessage() . "\n";
}
?>