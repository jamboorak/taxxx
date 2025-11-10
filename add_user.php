<?php
session_start();
require_once 'config.php';
require_once 'auth.php';

// Only admin can add users
requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

try {
    // Get form data
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? 'user';
    $address = trim($_POST['address'] ?? '');

    // Validation
    if (empty($full_name) || empty($email) || empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit();
    }

    // Validate password length
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long']);
        exit();
    }

    // Validate user type
    if (!in_array($user_type, ['admin', 'user'])) {
        $user_type = 'user';
    }

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit();
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $pdo->prepare("
        INSERT INTO users (username, email, password, full_name, user_type, address, is_active, created_at)
        VALUES (?, ?, ?, ?, ?, ?, TRUE, NOW())
    ");

    $stmt->execute([
        $username,
        $email,
        $hashed_password,
        $full_name,
        $user_type,
        $address
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'User added successfully',
        'user_id' => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {
    error_log("Error adding user: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred. Please try again.'
    ]);
} catch (Exception $e) {
    error_log("Error adding user: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again.'
    ]);
}
?>

