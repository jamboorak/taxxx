<?php
// One-time script to create an additional admin account
// Usage (example):
//   http://localhost/Tax/create_admin.php?username=admin2&email=admin2@example.com&password=admin123&full_name=Second+Admin

require_once __DIR__ . '/config.php';

// Ensure DB connection is available
if (!$pdo) {
    http_response_code(500);
    echo "Database connection not available. Please check config.php or run setup_database.php.";
    exit;
}

// Only allow GET for simplicity; in production prefer POST and CSRF checks
$username = isset($_GET['username']) ? trim($_GET['username']) : '';
$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$password = isset($_GET['password']) ? (string)$_GET['password'] : '';
$fullName = isset($_GET['full_name']) ? trim($_GET['full_name']) : 'Administrator';

$errors = [];
if ($username === '') { $errors[] = 'username is required'; }
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'valid email is required'; }
if ($password === '' || strlen($password) < 6) { $errors[] = 'password must be at least 6 characters'; }

header('Content-Type: text/plain; charset=utf-8');

if ($errors) {
    echo "Cannot create admin due to:\n- " . implode("\n- ", $errors) . "\n\n";
    echo "Example: /Tax/create_admin.php?username=admin2&email=admin2@example.com&password=admin123&full_name=Second+Admin\n";
    exit;
}

try {
    // Check if username/email already exists
    $checkStmt = $pdo->prepare("SELECT id, username, email FROM users WHERE username = ? OR email = ? LIMIT 1");
    $checkStmt->execute([$username, $email]);
    $existing = $checkStmt->fetch();
    if ($existing) {
        echo "User already exists with username/email.\n";
        echo "username: {$existing['username']} | email: {$existing['email']}\n";
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $insertStmt = $pdo->prepare(
        "INSERT INTO users (username, email, password, full_name, user_type, is_active) VALUES (?, ?, ?, ?, 'admin', TRUE)"
    );
    $insertStmt->execute([$username, $email, $passwordHash, $fullName]);

    echo "✓ Admin account created successfully.\n";
    echo "You can now login:\n";
    echo "- Username: {$username}\n";
    echo "- Password: (the one you provided)\n\n";
    echo "Admin dashboard: /Tax/dashboard.php\n\n";
    echo "Security note: Delete this file (create_admin.php) after use.";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . $e->getMessage();
}
?>



