<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
requireAdmin();
$user = getCurrentUser();
$stats = getDashboardStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Barangay Del Remedio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <link rel="stylesheet" href="style.css">
                <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">
    <div class="admin-container">
        <nav class="admin-sidebar">
            <div class="sidebar-header">
                <div class="brand-info">
                    <i class="fas fa-landmark"></i>
                    <div>
                        <h4>Barangay Del Remedio</h4>
                        <small>Admin Portal</small>
                    </div>
                </div>
            </div>
            
            <ul class="sidebar-nav">
                <li class="nav-item active">
                    <a href="dashboard.php" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="taxes.php" class="nav-link">
                        <i class="fas fa-receipt"></i>
                        <span>Tax Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="projects.php" class="nav-link">
                        <i class="fas fa-project-diagram"></i>
                        <span>Projects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="reports.php" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="users.php" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>User Management</span>
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="user-details">
                        <strong><?php echo $user['full_name']; ?></strong>
                        <small>Administrator</small>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </nav>

        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <button class="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Admin Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="btn btn-notification">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                        <button class="btn btn-profile">
                            <i class="fas fa-user-circle"></i>
                            <?php echo $user['username']; ?>
                        </button>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                <!-- Quick Stats -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card revenue">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <h3>₱<?php echo number_format($stats['total_taxes'], 2); ?></h3>
                                <p>Total Revenue</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> 12.5%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card budget">
                            <div class="stat-icon">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <div class="stat-content">
                                <h3>₱<?php echo number_format($stats['total_budget'], 2); ?></h3>
                                <p>Annual Budget</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> 8.3%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card projects">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <h3><?php echo $stats['completed_projects']; ?></h3>
                                <p>Completed Projects</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> 15.2%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card compliance">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-content">
                                <h3><?php echo $stats['tax_compliance']; ?>%</h3>
                                <p>Tax Compliance</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> 5.7%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-8">
                        <div class="admin-card">
                            <div class="card-header">
                                <h4>Revenue vs Expenditure</h4>
                                <div class="card-actions">
                                    <select class="form-select form-select-sm">
                                        <option>2024</option>
                                        <option>2023</option>
                                        <option>2022</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="revenueExpenditureChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="admin-card">
                            <div class="card-header">
                                <h4>Budget Allocation</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="budgetChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity & Quick Actions -->
                <div class="row g-4">
                    <div class="col-xl-6">
                        <div class="admin-card">
                            <div class="card-header">
                                <h4>Recent Activity</h4>
                                <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="activity-list">
                                    <div class="activity-item">
                                        <div class="activity-icon success">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="activity-content">
                                            <p>New tax payment received from Juan Dela Cruz</p>
                                            <small>2 minutes ago</small>
                                        </div>
                                        <div class="activity-amount">
                                            ₱2,500.00
                                        </div>
                                    </div>
                                    <div class="activity-item">
                                        <div class="activity-icon warning">
                                            <i class="fas fa-exclamation"></i>
                                        </div>
                                        <div class="activity-content">
                                            <p>Project "Road Rehabilitation" is behind schedule</p>
                                            <small>1 hour ago</small>
                                        </div>
                                    </div>
                                    <div class="activity-item">
                                        <div class="activity-icon info">
                                            <i class="fas fa-info"></i>
                                        </div>
                                        <div class="activity-content">
                                            <p>New user registration: Maria Santos</p>
                                            <small>3 hours ago</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="admin-card">
                            <div class="card-header">
                                <h4>Quick Actions</h4>
                            </div>
                            <div class="card-body">
                                <div class="quick-actions">
                                    <a href="taxes.php" class="action-card">
                                        <div class="action-icon">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <span>Manage Taxes</span>
                                    </a>
                                    <a href="projects.php" class="action-card">
                                        <div class="action-icon">
                                            <i class="fas fa-project-diagram"></i>
                                        </div>
                                        <span>Add Project</span>
                                    </a>
                                    <a href="reports.php" class="action-card">
                                        <div class="action-icon">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <span>Generate Report</span>
                                    </a>
                                    <a href="users.php" class="action-card">
                                        <div class="action-icon">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <span>Add User</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin.js"></script>
    <script>
    // Initialize admin charts
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue vs Expenditure Chart
        const revenueCtx = document.getElementById('revenueExpenditureChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Revenue (₱)',
                        data: [450000, 520000, 480000, 510000, 490000, 397350, 420000, 460000, 500000, 480000, 520000, 550000],
                        borderColor: '#27ae60',
                        backgroundColor: 'rgba(39, 174, 96, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Expenditure (₱)',
                        data: [420000, 480000, 450000, 470000, 460000, 380000, 400000, 420000, 450000, 430000, 460000, 480000],
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Revenue vs Expenditure 2024',
                        font: { size: 16, weight: 'bold' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + (value / 1000).toLocaleString() + 'K';
                            }
                        }
                    }
                }
            }
        });

        // Budget Chart
        const budgetCtx = document.getElementById('budgetChart').getContext('2d');
        new Chart(budgetCtx, {
            type: 'doughnut',
            data: {
                labels: ['Health Services', 'Education', 'Infrastructure', 'Waste Management', 'Peace and Order'],
                datasets: [{
                    data: [25, 20, 30, 15, 10],
                    backgroundColor: [
                        '#3498db', '#2ecc71', '#9b59b6', '#f1c40f', '#e67e22'
                    ],
                    borderColor: 'white',
                    borderWidth: 3,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '60%'
            }
        });
    });
    </script>
</body>
</html>