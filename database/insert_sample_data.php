<?php
/**
 * Insert Sample Cave Explorations Data
 * 
 * This script inserts sample data into the cave_explorations table.
 */

// Include database connection
require_once __DIR__ . '/../includes/db_connect.php';

try {
    // Sample cave explorations data
    $sample_data = [
        [
            'name' => 'Beginner Cave Tour',
            'price' => 89.00,
            'image' => 'assets/images/expedition-1.jpg'
        ],
        [
            'name' => 'Underground River Adventure',
            'price' => 149.00,
            'image' => 'assets/images/expedition-2.jpg'
        ],
        [
            'name' => 'Advanced Spelunking Expedition',
            'price' => 249.00,
            'image' => 'assets/images/expedition-3.jpg'
        ],
        [
            'name' => 'Crystal Cave Discovery',
            'price' => 199.00,
            'image' => 'assets/images/expedition-4.jpg'
        ],
        [
            'name' => 'Deep Cave Photography Tour',
            'price' => 179.00,
            'image' => 'assets/images/expedition-5.jpg'
        ]
    ];

    // Clear existing data
    $conn->exec("DELETE FROM cave_explorations");
    echo "<h2>Inserting Sample Cave Explorations Data</h2>";
    
    // Insert sample data
    $stmt = $conn->prepare("INSERT INTO cave_explorations (name, price, image) VALUES (?, ?, ?)");
    
    foreach ($sample_data as $exploration) {
        $stmt->execute([
            $exploration['name'],
            $exploration['price'],
            $exploration['image']
        ]);
        echo "<p>Inserted: " . htmlspecialchars($exploration['name']) . " - $" . $exploration['price'] . "</p>";
    }
    
    echo "<p><strong>Sample data inserted successfully!</strong></p>";
    echo "<p><a href='../index.php'>View Website</a></p>";
    
} catch(PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>