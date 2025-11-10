<?php
// Authentication and authorization functions
// Note: This file should be included after config.php which provides $pdo

if (!function_exists('requireLogin')) {
    function requireLogin() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: index.php");
            exit();
        }
    }
}

if (!function_exists('requireAdmin')) {
    function requireAdmin() {
        requireLogin();
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            header("Location: index.php");
            exit();
        }
    }
}

if (!defined('ROLE_ADMIN')) {
    define('ROLE_ADMIN', 'admin');
}

if (!defined('ROLE_USER')) {
    define('ROLE_USER', 'user');
}

if (!function_exists('checkAuth')) {
    function checkAuth($requiredRole) {
        requireLogin();
        if ($requiredRole === ROLE_ADMIN && (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin')) {
            header("Location: index.php");
            exit();
        }
    }
}

if (!function_exists('getCurrentUser')) {
    function getCurrentUser() {
        if (function_exists('getUserData')) {
            return getUserData();
        }
        if (isset($_SESSION['user_id'])) {
            global $pdo;
            try {
                $stmt = $pdo->prepare("SELECT id, username, email, full_name, user_type, phone, address FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                return $stmt->fetch();
            } catch (PDOException $e) {
                error_log("Error fetching user data: " . $e->getMessage());
                return null;
            }
        }
        return null;
    }
}
?>
