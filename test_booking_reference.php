<?php
require_once 'includes/db_connect.php';
require_once 'includes/booking_helper.php';

echo "<h2>Testing Professional Booking Reference Generation</h2>";

// Test the booking reference generation
echo "<h3>Current Booking References:</h3>";
try {
    $stmt = $pdo->prepare("SELECT id, booking_reference, created_at FROM bookings ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Database ID</th><th>Professional Reference</th><th>Created At</th><th>Formatted Display</th></tr>";
    
    foreach ($bookings as $booking) {
        $formatted = formatBookingReference($booking['booking_reference'], $booking['id']);
        echo "<tr>";
        echo "<td>#{$booking['id']}</td>";
        echo "<td>{$booking['booking_reference']}</td>";
        echo "<td>{$booking['created_at']}</td>";
        echo "<td><strong>{$formatted}</strong></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test generating a new reference
    echo "<h3>Next Booking Reference Would Be:</h3>";
    $next_reference = generateBookingReference($pdo);
    echo "<p><strong>Next booking will get reference: {$next_reference}</strong></p>";
    
    echo "<h3>Format Explanation:</h3>";
    echo "<ul>";
    echo "<li><strong>SRC</strong> - San Rafael Cave prefix</li>";
    echo "<li><strong>" . date('Y') . "</strong> - Current year</li>";
    echo "<li><strong>" . date('m') . "</strong> - Current month (" . date('F') . ")</li>";
    echo "<li><strong>NNNN</strong> - Sequential number for this month (4 digits with leading zeros)</li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php'>Back to Website</a> | <a href='customer/my_bookings.php'>View My Bookings</a></p>";
?>