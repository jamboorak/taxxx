<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
requireLogin();
$user = getUserData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Barangay Del Remedio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="user.css">
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
                                <li><a class="dropdown-item" href="../php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="user-main">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header mb-5">
                <h1>My Profile</h1>
                <p class="lead">Manage your personal information and account settings</p>
            </div>

            <div class="row">
                <!-- Profile Sidebar -->
                <div class="col-lg-4 mb-4">
                    <div class="user-card">
                        <div class="card-body text-center">
                            <div class="profile-avatar mb-3">
                                <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto">
                                    <span class="text-white fs-2"><?php echo strtoupper(substr($user['full_name'], 0, 2)); ?></span>
                                </div>
                            </div>
                            <h4><?php echo $user['full_name']; ?></h4>
                            <p class="text-muted">Resident</p>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary" onclick="editProfile()">
                                    <i class="fas fa-edit me-2"></i>Edit Profile
                                </button>
                                <button class="btn btn-outline-secondary" onclick="changePassword()">
                                    <i class="fas fa-lock me-2"></i>Change Password
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Account Summary -->
                    <div class="user-card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Account Summary</h5>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Member Since</span>
                                    <span class="text-muted">Jan 15, 2023</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Last Login</span>
                                    <span class="text-muted">Today, 10:30 AM</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Tax Compliance</span>
                                    <span class="badge bg-success">85%</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Properties</span>
                                    <span class="text-muted">2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="col-lg-8">
                    <!-- Personal Information -->
                    <div class="user-card mb-4">
                        <div class="card-header">
                            <h4><i class="fas fa-user me-2"></i>Personal Information</h4>
                        </div>
                        <div class="card-body">
                            <form id="profileForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" value="<?php echo $user['full_name']; ?>" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" value="<?php echo $user['email']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" value="<?php echo $user['username']; ?>" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" value="+63 912 345 6789" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" rows="3" readonly>Purok 3, Barangay Del Remedio, San Pablo City, Laguna</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Birth Date</label>
                                        <input type="date" class="form-control" value="1985-06-15" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Gender</label>
                                        <input type="text" class="form-control" value="Male" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Civil Status</label>
                                        <input type="text" class="form-control" value="Married" readonly>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Property Information -->
                    <div class="user-card">
                        <div class="card-header">
                            <h4><i class="fas fa-home me-2"></i>Property Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Property Type</th>
                                            <th>Location</th>
                                            <th>Assessed Value</th>
                                            <th>Tax Due</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Residential Lot</strong>
                                                <br><small>200 sqm</small>
                                            </td>
                                            <td>Purok 3</td>
                                            <td>₱250,000</td>
                                            <td>₱2,500</td>
                                            <td><span class="badge bg-success">Paid</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Commercial Space</strong>
                                                <br><small>50 sqm</small>
                                            </td>
                                            <td>Purok 2</td>
                                            <td>₱180,000</td>
                                            <td>₱1,800</td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Activity History -->
                    <div class="user-card mt-4">
                        <div class="card-header">
                            <h4><i class="fas fa-history me-2"></i>Recent Activity</h4>
                        </div>
                        <div class="card-body">
                            <div class="activity-timeline">
                                <div class="activity-item">
                                    <div class="activity-icon success">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="activity-content">
                                        <p>Tax payment for Residential Lot - Q2 2024</p>
                                        <small class="text-muted">June 15, 2024 • ₱2,500.00</small>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon info">
                                        <i class="fas fa-user-edit"></i>
                                    </div>
                                    <div class="activity-content">
                                        <p>Updated profile information</p>
                                        <small class="text-muted">June 10, 2024</small>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon warning">
                                        <i class="fas fa-exclamation"></i>
                                    </div>
                                    <div class="activity-content">
                                        <p>Tax reminder for Commercial Space - Q2 2024</p>
                                        <small class="text-muted">June 1, 2024 • Due: ₱1,800.00</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="<?php echo $user['full_name']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" value="<?php echo $user['email']; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" value="+63 912 345 6789">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Birth Date</label>
                                <input type="date" class="form-control" value="1985-06-15">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" rows="3">Purok 3, Barangay Del Remedio, San Pablo City, Laguna</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select">
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Civil Status</label>
                                <select class="form-select">
                                    <option>Single</option>
                                    <option selected>Married</option>
                                    <option>Widowed</option>
                                    <option>Separated</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" placeholder="Enter current password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" placeholder="Enter new password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" placeholder="Confirm new password">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="savePassword()">Update Password</button>
                </div>
            </div>
        </div>
    </div>

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
    function editProfile() {
        const modal = new bootstrap.Modal(document.getElementById('editProfileModal'));
        modal.show();
    }

    function changePassword() {
        const modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        modal.show();
    }

    function saveProfile() {
        // In a real application, this would save to the database
        alert('Profile updated successfully!');
        const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
        modal.hide();
    }

    function savePassword() {
        // In a real application, this would update the password
        alert('Password updated successfully!');
        const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
        modal.hide();
    }
    </script>
</body>
</html>