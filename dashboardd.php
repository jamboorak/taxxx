<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
requireLogin();

// Get user data
$user = getCurrentUser();
if (!$user) {
    header("Location: index.php");
    exit();
}

// Get user-specific data
$userStats = [
    'paid_taxes' => 12500,
    'pending_balance' => 2300,
    'properties' => 2,
    'compliance_rate' => 85
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Barangay Del Remedio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <link rel="stylesheet" href="style.css">
                <link rel="stylesheet" href="user.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="user-body">
    <header class="user-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="dashboard.php">
                    <i class="fas fa-landmark me-2"></i>
                    Barangay Del Remedio
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboardd.php">
                                <i class="fas fa-home me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="taxess.php">
                                <i class="fas fa-receipt me-1"></i>My Taxes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="projectss.php">
                                <i class="fas fa-project-diagram me-1"></i>Projects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">
                                <i class="fas fa-user me-1"></i>Profile
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-nav">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                <?php echo $user['full_name']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>My Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-history me-2"></i>Activity</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="user-main">
        <div class="container">
            <!-- Welcome Section -->
            <div class="welcome-section mb-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1>Welcome back, <?php echo $user['full_name']; ?>! 👋</h1>
                        <p class="lead">Here's what's happening in Barangay Del Remedio today.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="user-quick-stats">
                            <div class="stat-item">
                                <small>Tax Compliance</small>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: <?php echo $userStats['compliance_rate']; ?>%">
                                        <?php echo $userStats['compliance_rate']; ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Stats -->
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="user-stat-card">
                        <div class="stat-icon paid">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>₱<?php echo number_format($userStats['paid_taxes']); ?></h3>
                            <p>Taxes Paid (2024)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="user-stat-card">
                        <div class="stat-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3>₱<?php echo number_format($userStats['pending_balance']); ?></h3>
                            <p>Pending Balance</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="user-stat-card">
                        <div class="stat-icon projects">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $userStats['properties']; ?></h3>
                            <p>Properties</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="user-stat-card">
                        <div class="stat-icon compliance">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo $userStats['compliance_rate']; ?>%</h3>
                            <p>Compliance Rate</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Public Information -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="user-card">
                        <div class="card-header">
                            <h4><i class="fas fa-chart-line me-2"></i>Barangay Financial Overview</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="publicRevenueChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="user-card">
                        <div class="card-header">
                            <h4><i class="fas fa-bullhorn me-2"></i>Recent Announcements</h4>
                        </div>
                        <div class="card-body">
                            <div class="announcement-list">
                                <div class="announcement-item urgent">
                                    <div class="announcement-icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="announcement-content">
                                        <h6>Tax Payment Deadline</h6>
                                        <p>Q2 2024 tax payments due by June 30</p>
                                        <small>2 days ago</small>
                                    </div>
                                </div>
                                <div class="announcement-item info">
                                    <div class="announcement-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="announcement-content">
                                        <h6>Road Maintenance</h6>
                                        <p>Purok 3 road rehabilitation starts next week</p>
                                        <small>1 week ago</small>
                                    </div>
                                </div>
                                <div class="announcement-item success">
                                    <div class="announcement-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="announcement-content">
                                        <h6>Health Center</h6>
                                        <p>Renovation completed ahead of schedule</p>
                                        <small>2 weeks ago</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Projects -->
            <div class="row g-4 mt-4">
                <div class="col-md-6">
                    <div class="user-card">
                        <div class="card-header">
                            <h4><i class="fas fa-bolt me-2"></i>Quick Actions</h4>
                        </div>
                        <div class="card-body">
                            <div class="user-actions">
                                <a href="taxes.php" class="user-action-btn primary">
                                    <i class="fas fa-receipt"></i>
                                    <span>Pay Taxes</span>
                                </a>
                                <a href="taxes.php" class="user-action-btn secondary">
                                    <i class="fas fa-file-invoice"></i>
                                    <span>View Tax History</span>
                                </a>
                                <a href="projects.php" class="user-action-btn success">
                                    <i class="fas fa-project-diagram"></i>
                                    <span>Track Projects</span>
                                </a>
                                <a href="profile.php" class="user-action-btn info">
                                    <i class="fas fa-user-edit"></i>
                                    <span>Update Profile</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="user-card">
                        <div class="card-header">
                            <h4><i class="fas fa-tasks me-2"></i>Active Projects in Your Area</h4>
                        </div>
                        <div class="card-body">
                            <div class="user-projects">
                                <div class="project-item">
                                    <div class="project-info">
                                        <h6>Road Rehabilitation - Purok 3</h6>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" style="width: 75%"></div>
                                        </div>
                                        <small>75% complete • Ends Jun 15, 2024</small>
                                    </div>
                                </div>
                                <div class="project-item">
                                    <div class="project-info">
                                        <h6>Public Park Development</h6>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" style="width: 60%"></div>
                                        </div>
                                        <small>60% complete • Ends Jul 30, 2024</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="user-footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>&copy; 2024 Barangay Del Remedio. Serving our community with transparency.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Help Center</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="user.js"></script>
    <script>
    // Initialize charts for user dashboard
    document.addEventListener('DOMContentLoaded', function() {
        // Public Revenue Chart
        const publicRevenueCtx = document.getElementById('publicRevenueChart').getContext('2d');
        new Chart(publicRevenueCtx, {
            type: 'bar',
            data: {
                labels: ['Health', 'Education', 'Infrastructure', 'Environment', 'Security'],
                datasets: [{
                    label: 'Budget Allocation (₱)',
                    data: [875000, 700000, 1050000, 525000, 350000],
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#9b59b6',
                        '#f1c40f',
                        '#e67e22'
                    ],
                    borderColor: 'white',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: '2024 Public Budget Allocation',
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
    });
    </script>
</body>
</html>