<?php
/**
 * Booking Helper Functions
 * 
 * Contains utility functions for booking operations
 */

/**
 * Generate a professional booking reference ID
 * Format: SRC-2025MM-NNNN (e.g., SRC-202501-0001)
 * 
 * @param PDO $pdo Database connection
 * @return string Professional booking reference
 */
function generateBookingReference($pdo) {
    $current_year = date('Y');
    $current_month = date('m');
    $year_month = $current_year . $current_month;
    
    // Get the next sequential number for this month
    $sql = "SELECT COUNT(*) + 1 as next_number 
            FROM bookings 
            WHERE booking_reference LIKE 'SRC-" . $year_month . "-%'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $next_number = $result['next_number'];
    
    // Format the number with leading zeros (4 digits)
    $formatted_number = str_pad($next_number, 4, '0', STR_PAD_LEFT);
    
    // Create the final booking reference
    $booking_reference = "SRC-{$year_month}-{$formatted_number}";
    
    return $booking_reference;
}

/**
 * Format booking reference for display
 * 
 * @param string $booking_reference The booking reference
 * @param int $fallback_id Fallback ID if no reference exists
 * @return string Formatted booking reference
 */
function formatBookingReference($booking_reference, $fallback_id = null) {
    if (!empty($booking_reference)) {
        return $booking_reference;
    }
    
    // Fallback to old format if no reference exists
    return $fallback_id ? "#{$fallback_id}" : "#N/A";
}
?>