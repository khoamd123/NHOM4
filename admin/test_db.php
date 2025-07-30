<?php
// Test kết nối database
echo "<h2>Test Database Connection</h2>";

try {
    require_once __DIR__ . '/models/db.php';
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test query
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>📊 Total users in database: " . $result['count'] . "</p>";
    
    // Test userModel functions
    require_once __DIR__ . '/models/userModel.php';
    $users = getAllUsers();
    echo "<p>👥 Users loaded: " . count($users) . "</p>";
    
    if (count($users) > 0) {
        echo "<h3>Sample Users:</h3>";
        echo "<ul>";
        foreach (array_slice($users, 0, 3) as $user) {
            echo "<li>ID: {$user['id']} - Name: {$user['name']} - Email: {$user['email']} - Status: {$user['status']}</li>";
        }
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?> 