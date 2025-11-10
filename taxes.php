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
    <title>Tax Management - Barangay Del Remedio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <link rel="stylesheet" href="style.css">
                <link rel="stylesheet" href="admin.css">
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
                <li class="nav-item active">
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
                    <h1>Tax Management</h1>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="btn btn-primary" onclick="showAddTaxModal()">
                            <i class="fas fa-plus me-2"></i>Add Tax Record
                        </button>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                <!-- Tax Summary Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card revenue">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <h3>₱2,847,350</h3>
                                <p>Total Collected</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> 12.5%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card budget">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <h3>₱245,680</h3>
                                <p>Pending Collections</p>
                                <span class="stat-trend down">
                                    <i class="fas fa-arrow-down"></i> 8.3%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card projects">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <h3>1,247</h3>
                                <p>Taxpayers</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> 5.2%
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
                                <h3>78%</h3>
                                <p>Compliance Rate</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> 3.7%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tax Records Table -->
                <div class="admin-card">
                    <div class="card-header">
                        <h4>Tax Records</h4>
                        <div class="card-actions">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" class="form-control" placeholder="Search taxpayers...">
                                <button class="btn btn-outline-primary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Taxpayer</th>
                                        <th>Tax Type</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <span class="text-white">JD</span>
                                                </div>
                                                <div>
                                                    <strong>Juan Dela Cruz</strong>
                                                    <br><small class="text-muted">Purok 3</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Real Property Tax</td>
                                        <td>₱2,500.00</td>
                                        <td>Jun 30, 2024</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <span class="text-white">MS</span>
                                                </div>
                                                <div>
                                                    <strong>Maria Santos</strong>
                                                    <br><small class="text-muted">Purok 2</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Business Tax</td>
                                        <td>₱5,200.00</td>
                                        <td>Jun 30, 2024</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <span class="text-white">PR</span>
                                                </div>
                                                <div>
                                                    <strong>Pedro Reyes</strong>
                                                    <br><small class="text-muted">Purok 1</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Community Tax</td>
                                        <td>₱350.00</td>
                                        <td>Jun 30, 2024</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Tax Modal -->
    <div class="modal fade" id="addTaxModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Tax Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addTaxForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Taxpayer</label>
                                <select class="form-select" required>
                                    <option value="">Select Taxpayer</option>
                                    <option value="1">Juan Dela Cruz</option>
                                    <option value="2">Maria Santos</option>
                                    <option value="3">Pedro Reyes</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tax Type</label>
                                <select class="form-select" required>
                                    <option value="">Select Tax Type</option>
                                    <option value="property">Real Property Tax</option>
                                    <option value="business">Business Tax</option>
                                    <option value="community">Community Tax</option>
                                    <option value="professional">Professional Tax</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Amount</label>
                                <input type="number" class="form-control" placeholder="Enter amount" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Due Date</label>
                                <input type="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Enter description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveTaxRecord()">Save Record</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin.js"></script>
</body>
</html>