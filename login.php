<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validation
    $errors = [];
    
    if (empty($username) || empty($password)) {
        $errors[] = "Username and password are required.";
    }
    
    if (count($errors) === 0) {
        try {
            // Check if user exists (username or email)
            $stmt = $pdo->prepare("SELECT id, username, email, password, full_name, user_type, is_active FROM users WHERE (username = ? OR email = ?) AND is_active = TRUE");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['logged_in'] = true;
                $_SESSION['full_name'] = $user['full_name'];
                
                $_SESSION['message'] = 'Login successful! Welcome back, ' . $user['full_name'] . '!';
                $_SESSION['message_type'] = 'success';
                
                // Redirect based on user type
                if ($user['user_type'] === 'admin') {
                    header("Location: dashboard.php");
                } else {
                    header("Location: dashboardd.php");
                }
                exit();
                
            } else {
                $_SESSION['message'] = 'Invalid username or password!';
                $_SESSION['message_type'] = 'error';
                header("Location: index.php");
                exit();
            }
            
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
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
