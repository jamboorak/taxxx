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
    <title>Project Management - Barangay Del Remedio</title>
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
                <li class="nav-item">
                    <a href="taxes.php" class="nav-link">
                        <i class="fas fa-receipt"></i>
                        <span>Tax Management</span>
                    </a>
                </li>
                <li class="nav-item active">
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
                    <h1>Project Management</h1>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="btn btn-primary" onclick="showAddProjectModal()">
                            <i class="fas fa-plus me-2"></i>Add Project
                        </button>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                <!-- Project Summary -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card revenue">
                            <div class="stat-icon">
                                <i class="fas fa-list-check"></i>
                            </div>
                            <div class="stat-content">
                                <h3>24</h3>
                                <p>Total Projects</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> 15.2%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card budget">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <h3>18</h3>
                                <p>Completed</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> 8.3%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card projects">
                            <div class="stat-icon">
                                <i class="fas fa-spinner"></i>
                            </div>
                            <div class="stat-content">
                                <h3>4</h3>
                                <p>In Progress</p>
                                <span class="stat-trend down">
                                    <i class="fas fa-arrow-down"></i> 2.1%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card compliance">
                            <div class="stat-icon">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <div class="stat-content">
                                <h3>₱3.5M</h3>
                                <p>Total Budget</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> 12.7%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projects Table -->
                <div class="admin-card">
                    <div class="card-header">
                        <h4>Active Projects</h4>
                        <div class="card-actions">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" class="form-control" placeholder="Search projects...">
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
                                        <th>Project Name</th>
                                        <th>Budget</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Road Rehabilitation - Purok 3</strong>
                                            <br><small class="text-muted">Infrastructure</small>
                                        </td>
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
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Public Park Development</strong>
                                            <br><small class="text-muted">Environment</small>
                                        </td>
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
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Drainage System Improvement</strong>
                                            <br><small class="text-muted">Infrastructure</small>
                                        </td>
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
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
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

    <!-- Add Project Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addProjectForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Project Name</label>
                                <input type="text" class="form-control" placeholder="Enter project name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" required>
                                    <option value="">Select Category</option>
                                    <option value="infrastructure">Infrastructure</option>
                                    <option value="health">Health Services</option>
                                    <option value="education">Education</option>
                                    <option value="environment">Environment</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Budget</label>
                                <input type="number" class="form-control" placeholder="Enter budget" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" required>
                                    <option value="planning">Planning</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Enter project description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveProject()">Save Project</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin.js"></script>
</body>
</html>