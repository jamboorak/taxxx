<?php
/**
 * Database Setup Script
 * Run this file once to create the database and all tables
 * Access via browser: http://localhost/Tax/setup_database.php
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'barangay_fiscal_portal';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Read SQL file
$sqlFile = __DIR__ . '/database_schema.sql';
$sql = file_get_contents($sqlFile);

if (!$sql) {
    die("Error: Could not read database_schema.sql file!");
}

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Database Setup</h1>";
    echo "<p>Setting up database...</p>";
    
    // Split SQL into individual statements
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // Ignore errors for CREATE TABLE IF NOT EXISTS and similar
                if (strpos($e->getMessage(), 'already exists') === false && 
                    strpos($e->getMessage(), 'Duplicate entry') === false) {
                    echo "<p style='color: orange;'>Warning: " . $e->getMessage() . "</p>";
                }
            }
        }
    }
    
    echo "<h2 style='color: green;'>✓ Database setup completed successfully!</h2>";
    echo "<p><strong>Default Admin Credentials:</strong></p>";
    echo "<ul>";
    echo "<li>Username: <strong>admin</strong></li>";
    echo "<li>Password: <strong>admin123</strong></li>";
    echo "</ul>";
    echo "<p><a href='index.php'>Go to Homepage</a> | <a href='dashboard.php'>Go to Admin Dashboard</a></p>";
    echo "<p style='color: red;'><strong>IMPORTANT:</strong> Delete or secure this setup_database.php file after setup!</p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>Make sure MySQL is running and credentials are correct.</p>";
}
?>


