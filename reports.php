<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
checkAuth(ROLE_ADMIN);
$user = getUserData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Barangay Del Remedio</title>
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
                <li class="nav-item">
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
                <li class="nav-item active">
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
                    <h1>Reports & Analytics</h1>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="btn btn-primary" onclick="generateReport()">
                            <i class="fas fa-download me-2"></i>Generate Report
                        </button>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                <!-- Report Filters -->
                <div class="admin-card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Report Type</label>
                                <select class="form-select" id="reportType">
                                    <option value="financial">Financial Summary</option>
                                    <option value="tax">Tax Collection</option>
                                    <option value="project">Project Progress</option>
                                    <option value="compliance">Compliance Report</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date Range</label>
                                <select class="form-select" id="dateRange">
                                    <option value="current_month">Current Month</option>
                                    <option value="last_month">Last Month</option>
                                    <option value="current_quarter">Current Quarter</option>
                                    <option value="current_year" selected>Current Year</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Format</label>
                                <select class="form-select" id="reportFormat">
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" onclick="generateReport()">
                                    <i class="fas fa-chart-bar me-2"></i>Generate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-6">
                        <div class="admin-card">
                            <div class="card-header">
                                <h4>Monthly Revenue Trend</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="revenueTrendChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="admin-card">
                            <div class="card-header">
                                <h4>Tax Collection by Type</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="taxTypeChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Progress -->
                <div class="admin-card">
                    <div class="card-header">
                        <h4>Project Progress Report</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Budget</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Spent</th>
                                        <th>Remaining</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Road Rehabilitation - Purok 3</td>
                                        <td>₱520,000</td>
                                        <td>Mar 1, 2024</td>
                                        <td>Jun 15, 2024</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-warning" style="width: 75%"></div>
                                            </div>
                                            <small>75%</small>
                                        </td>
                                        <td><span class="badge bg-warning">In Progress</span></td>
                                        <td>₱390,000</td>
                                        <td>₱130,000</td>
                                    </tr>
                                    <tr>
                                        <td>Public Park Development</td>
                                        <td>₱450,000</td>
                                        <td>Apr 5, 2024</td>
                                        <td>Jul 30, 2024</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-info" style="width: 60%"></div>
                                            </div>
                                            <small>60%</small>
                                        </td>
                                        <td><span class="badge bg-info">In Progress</span></td>
                                        <td>₱270,000</td>
                                        <td>₱180,000</td>
                                    </tr>
                                    <tr>
                                        <td>Drainage System Improvement</td>
                                        <td>₱280,000</td>
                                        <td>May 10, 2024</td>
                                        <td>Sep 20, 2024</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-secondary" style="width: 30%"></div>
                                            </div>
                                            <small>30%</small>
                                        </td>
                                        <td><span class="badge bg-secondary">Planning</span></td>
                                        <td>₱84,000</td>
                                        <td>₱196,000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin.js"></script>
    <script>
    // Initialize charts
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Trend Chart
        const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Monthly Revenue (₱)',
                    data: [450000, 520000, 480000, 510000, 490000, 397350, 420000, 460000, 500000, 480000, 520000, 550000],
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39, 174, 96, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Tax Type Chart
        const taxCtx = document.getElementById('taxTypeChart').getContext('2d');
        new Chart(taxCtx, {
            type: 'doughnut',
            data: {
                labels: ['Property Tax', 'Business Tax', 'Community Tax', 'Professional Tax'],
                datasets: [{
                    data: [45, 30, 15, 10],
                    backgroundColor: ['#3498db', '#2ecc71', '#9b59b6', '#f1c40f']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });

    function generateReport() {
        const reportType = document.getElementById('reportType').value;
        const dateRange = document.getElementById('dateRange').value;
        const format = document.getElementById('reportFormat').value;
        
        AdminUtils.showToast(`Generating ${reportType} report for ${dateRange} in ${format.toUpperCase()} format...`, 'info');
        // In real application, this would generate and download the report
    }
    </script>
</body>
</html>