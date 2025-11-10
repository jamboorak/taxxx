<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 'barangay_fiscal_portal';
$username = 'root';
$password = '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Test connection
    $pdo->query("SELECT 1");
    
} catch(PDOException $e) {
    // If database doesn't exist, try to connect without database to show setup page
    if (strpos($e->getMessage(), "Unknown database") !== false) {
        error_log("Database not found. Please run setup_database.php first.");
        // Don't die - allow page to load with fallback data
        $pdo = null;
    } else {
        error_log("Database connection failed: " . $e->getMessage());
        $pdo = null;
    }
}

// Get user data
function getUserData() {
    global $pdo;
    
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id, username, email, full_name, user_type, phone, address FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching user data: " . $e->getMessage());
        return null;
    }
}

// Get dashboard statistics - UPDATED TO MATCH YOUR DATABASE SCHEMA
function getDashboardStats() {
    global $pdo;
    
    // If database is not connected, return default stats
    if (!$pdo) {
        return getDefaultStats();
    }
    
    $stats = [];
    
    try {
        // Total tax collection for current year - UPDATED
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(amount_paid), 0) as total_taxes 
            FROM tax_records 
            WHERE status = 'paid' AND period_year = YEAR(CURDATE())
        ");
        $stmt->execute();
        $stats['total_taxes'] = $stmt->fetchColumn();

        // Total budget - UPDATED
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(allocated_amount), 0) as total_budget 
            FROM budget_allocation 
            WHERE fiscal_year = YEAR(CURDATE())
        ");
        $stmt->execute();
        $stats['total_budget'] = $stmt->fetchColumn();

        // Completed projects - UPDATED
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as completed_projects 
            FROM projects 
            WHERE status = 'completed'
        ");
        $stmt->execute();
        $stats['completed_projects'] = $stmt->fetchColumn();

        // Tax compliance rate - UPDATED
        $stmt = $pdo->prepare("
            SELECT 
                ROUND(
                    (COUNT(CASE WHEN status = 'paid' THEN 1 END) * 100.0 / NULLIF(COUNT(*), 0)),
                    2
                ) as compliance_rate 
            FROM tax_records 
            WHERE period_year = YEAR(CURDATE())
        ");
        $stmt->execute();
        $stats['tax_compliance'] = $stmt->fetchColumn() ?: 0;

        // Budget allocations - UPDATED
        $stmt = $pdo->prepare("
            SELECT sector, allocation_percentage, allocated_amount 
            FROM budget_allocation 
            WHERE fiscal_year = YEAR(CURDATE()) 
            ORDER BY allocation_percentage DESC
        ");
        $stmt->execute();
        $stats['budget_allocations'] = $stmt->fetchAll();

        // Recent projects - UPDATED
        $stmt = $pdo->prepare("
            SELECT name as project_name, budget, status, completion_percentage 
            FROM projects 
            ORDER BY created_at DESC 
            LIMIT 4
        ");
        $stmt->execute();
        $stats['projects'] = $stmt->fetchAll();

        // Top expenditures - UPDATED
        $stmt = $pdo->prepare("
            SELECT category, SUM(amount) as amount 
            FROM expenditures 
            WHERE status = 'paid' 
            GROUP BY category 
            ORDER BY amount DESC 
            LIMIT 5
        ");
        $stmt->execute();
        $stats['top_expenditures'] = $stmt->fetchAll();

        // Project timeline - UPDATED
        $stmt = $pdo->prepare("
            SELECT name as project_name, start_date, end_date, budget, status 
            FROM projects 
            ORDER BY start_date DESC
        ");
        $stmt->execute();
        $stats['project_timeline'] = $stmt->fetchAll();

        // Additional stats from your database
        // Total properties
        $stmt = $pdo->prepare("SELECT COUNT(*) as total_properties FROM properties");
        $stmt->execute();
        $stats['total_properties'] = $stmt->fetchColumn();

        // Active users
        $stmt = $pdo->prepare("SELECT COUNT(*) as active_users FROM users WHERE is_active = TRUE");
        $stmt->execute();
        $stats['active_users'] = $stmt->fetchColumn();

        // Pending taxes
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(amount - amount_paid), 0) as pending_taxes 
            FROM tax_records 
            WHERE status IN ('pending', 'overdue') AND period_year = YEAR(CURDATE())
        ");
        $stmt->execute();
        $stats['pending_taxes'] = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Error fetching dashboard stats: " . $e->getMessage());
        // Return default values if database query fails
        return getDefaultStats();
    }
    
    return $stats;
}

// Default stats if database is not available
function getDefaultStats() {
    return [
        'total_taxes' => 2847350,
        'total_budget' => 3500000,
        'completed_projects' => 24,
        'tax_compliance' => 78,
        'total_properties' => 150,
        'active_users' => 45,
        'pending_taxes' => 450000,
        'budget_allocations' => [
            ['sector' => 'Health Services', 'allocation_percentage' => 25, 'allocated_amount' => 875000],
            ['sector' => 'Education', 'allocation_percentage' => 20, 'allocated_amount' => 700000],
            ['sector' => 'Infrastructure', 'allocation_percentage' => 30, 'allocated_amount' => 1050000],
            ['sector' => 'Waste Management', 'allocation_percentage' => 15, 'allocated_amount' => 525000],
            ['sector' => 'Peace and Order', 'allocation_percentage' => 10, 'allocated_amount' => 350000]
        ],
        'projects' => [
            ['project_name' => 'Barangay Health Center Renovation', 'budget' => 350000, 'status' => 'completed', 'completion_percentage' => 100],
            ['project_name' => 'Road Rehabilitation - Purok 3', 'budget' => 520000, 'status' => 'in-progress', 'completion_percentage' => 75],
            ['project_name' => 'Drainage System Improvement', 'budget' => 280000, 'status' => 'planning', 'completion_percentage' => 30],
            ['project_name' => 'Public Park Development', 'budget' => 450000, 'status' => 'in-progress', 'completion_percentage' => 60]
        ],
        'top_expenditures' => [
            ['category' => 'Infrastructure Projects', 'amount' => 1050000],
            ['category' => 'Health Services', 'amount' => 875000],
            ['category' => 'Education Programs', 'amount' => 700000],
            ['category' => 'Waste Management', 'amount' => 525000],
            ['category' => 'Peace and Order', 'amount' => 350000]
        ],
        'project_timeline' => [
            ['project_name' => 'Barangay Health Center Renovation', 'start_date' => '2024-01-15', 'end_date' => '2024-04-30', 'budget' => 350000, 'status' => 'completed'],
            ['project_name' => 'Road Rehabilitation - Purok 3', 'start_date' => '2024-03-01', 'end_date' => '2024-06-15', 'budget' => 520000, 'status' => 'in-progress'],
            ['project_name' => 'Drainage System Improvement', 'start_date' => '2024-05-10', 'end_date' => '2024-09-20', 'budget' => 280000, 'status' => 'planning'],
            ['project_name' => 'Public Park Development', 'start_date' => '2024-04-05', 'end_date' => '2024-07-30', 'budget' => 450000, 'status' => 'in-progress']
        ]
    ];
}

// Utility functions
function formatCurrency($amount) {
    return '₱' . number_format($amount, 2);
}

function calculateTax($type, $value) {
    switch($type) {
        case 'property':
            return $value * 0.01;
        case 'business':
            return $value * 0.02;
        case 'community':
            return 50 + ($value * 0.001);
        case 'professional':
            return 300;
        default:
            return 0;
    }
}

function getProjectStatusBadge($status) {
    $badges = [
        'planning' => 'bg-secondary',
        'in-progress' => 'bg-warning',
        'completed' => 'bg-success',
        'cancelled' => 'bg-danger'
    ];
    
    $text = [
        'planning' => 'Planning',
        'in-progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled'
    ];
    
    $class = $badges[$status] ?? 'bg-secondary';
    $statusText = $text[$status] ?? 'Unknown';
    
    return "<span class='badge $class'>$statusText</span>";
}

// New functions for your database schema
function getTaxTypes() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT id, name, description, rate, fixed_fee FROM tax_types WHERE is_active = TRUE");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching tax types: " . $e->getMessage());
        return [];
    }
}

function getProjectCategories() {
    return [
        'infrastructure' => 'Infrastructure',
        'health' => 'Health',
        'education' => 'Education',
        'environment' => 'Environment',
        'security' => 'Security'
    ];
}

function getBarangaySettings() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM settings");
        $stmt->execute();
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        return $settings;
    } catch (PDOException $e) {
        error_log("Error fetching settings: " . $e->getMessage());
        return [];
    }
}
?>