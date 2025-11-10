<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Validation
    $errors = [];
    
    if (empty($fullName) || empty($email) || empty($username) || empty($password)) {
        $errors[] = "All fields are required.";
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match!";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long!";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format!";
    }
    
    try {
        // Check if username or email already exists
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $checkStmt->execute([$username, $email]);
        
        if ($checkStmt->fetch()) {
            $errors[] = "Username or email already exists!";
        }
        
    } catch (PDOException $e) {
        error_log("Registration check error: " . $e->getMessage());
        $errors[] = "System error. Please try again later.";
    }
    
    if (count($errors) === 0) {
        try {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, user_type, created_at) VALUES (?, ?, ?, ?, 'user', NOW())");
            $stmt->execute([$username, $email, $hashedPassword, $fullName]);
            
            $_SESSION['message'] = 'Registration successful! You can now login.';
            $_SESSION['message_type'] = 'success';
            
            header("Location: index.php");
            exit();
            
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            $_SESSION['message'] = 'System error. Please try again later.';
            $_SESSION['message_type'] = 'error';
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['message'] = implode('<br>', $errors);
        $_SESSION['message_type'] = 'error';
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>