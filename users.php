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
    <title>User Management - Barangay Del Remedio</title>
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
                <li class="nav-item active">
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
                    <h1>User Management</h1>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="btn btn-primary" onclick="showAddUserModal()">
                            <i class="fas fa-user-plus me-2"></i>Add User
                        </button>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                <!-- User Statistics -->
                <div class="row g-4 mb-4">
                    <?php
                    // Fetch user statistics
                    try {
                                        $totalUsersStmt = $pdo->prepare("SELECT COUNT(*) as total FROM users");
                                        $totalUsersStmt->execute();
                                        $totalUsers = $totalUsersStmt->fetchColumn();

                                        $adminUsersStmt = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE user_type = 'admin'");
                                        $adminUsersStmt->execute();
                                        $adminUsers = $adminUsersStmt->fetchColumn();

                                        $activeUsersStmt = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE is_active = TRUE");
                                        $activeUsersStmt->execute();
                                        $activeUsers = $activeUsersStmt->fetchColumn();

                                        $inactiveUsersStmt = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE is_active = FALSE");
                                        $inactiveUsersStmt->execute();
                                        $inactiveUsers = $inactiveUsersStmt->fetchColumn();
                                    } catch (PDOException $e) {
                                        error_log("Error fetching user stats: " . $e->getMessage());
                                        $totalUsers = 0;
                                        $adminUsers = 0;
                                        $activeUsers = 0;
                                        $inactiveUsers = 0;
                                    }
                    ?>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card revenue">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <h3><?php echo number_format($totalUsers); ?></h3>
                                <p>Total Users</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> Registered
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card budget">
                            <div class="stat-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="stat-content">
                                <h3><?php echo number_format($adminUsers); ?></h3>
                                <p>Administrators</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> Active
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card projects">
                            <div class="stat-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="stat-content">
                                <h3><?php echo number_format($activeUsers); ?></h3>
                                <p>Active Users</p>
                                <span class="stat-trend up">
                                    <i class="fas fa-arrow-up"></i> Online
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="admin-stat-card compliance">
                            <div class="stat-icon">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div class="stat-content">
                                <h3><?php echo number_format($inactiveUsers); ?></h3>
                                <p>Inactive Users</p>
                                <span class="stat-trend down">
                                    <i class="fas fa-arrow-down"></i> Disabled
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="admin-card">
                    <div class="card-header">
                        <h4>User Accounts</h4>
                        <div class="card-actions">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" class="form-control" placeholder="Search users...">
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
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Registration Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch all users from database
                                    try {
                                        $usersStmt = $pdo->prepare("SELECT id, username, email, full_name, user_type, is_active, created_at, address FROM users ORDER BY created_at DESC");
                                        $usersStmt->execute();
                                        $allUsers = $usersStmt->fetchAll();

                                        if (empty($allUsers)) {
                                            echo '<tr><td colspan="6" class="text-center">No users found.</td></tr>';
                                        } else {
                                            foreach ($allUsers as $dbUser) {
                                                // Get initials for avatar
                                                $initials = '';
                                                $nameParts = explode(' ', $dbUser['full_name']);
                                                if (count($nameParts) >= 2) {
                                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
                                                } else {
                                                    $initials = strtoupper(substr($dbUser['full_name'], 0, 2));
                                                }

                                                // Determine avatar color based on user type
                                                $avatarBg = $dbUser['user_type'] === 'admin' ? 'bg-info' : 'bg-primary';
                                                
                                                // Format date
                                                $regDate = date('M d, Y', strtotime($dbUser['created_at']));
                                                
                                                // Get address or default
                                                $address = !empty($dbUser['address']) ? $dbUser['address'] : 'N/A';
                                                
                                                echo '<tr>';
                                                echo '<td>';
                                                echo '<div class="d-flex align-items-center">';
                                                echo '<div class="avatar-sm ' . $avatarBg . ' rounded-circle d-flex align-items-center justify-content-center me-3">';
                                                echo '<span class="text-white">' . htmlspecialchars($initials) . '</span>';
                                                echo '</div>';
                                                echo '<div>';
                                                echo '<strong>' . htmlspecialchars($dbUser['full_name']) . '</strong>';
                                                echo '<br><small class="text-muted">' . htmlspecialchars($address) . '</small>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</td>';
                                                echo '<td>' . htmlspecialchars($dbUser['email']) . '</td>';
                                                echo '<td>';
                                                if ($dbUser['user_type'] === 'admin') {
                                                    echo '<span class="badge bg-primary">Admin</span>';
                                                } else {
                                                    echo '<span class="badge bg-secondary">User</span>';
                                                }
                                                echo '</td>';
                                                echo '<td>';
                                                if ($dbUser['is_active']) {
                                                    echo '<span class="badge bg-success">Active</span>';
                                                } else {
                                                    echo '<span class="badge bg-danger">Inactive</span>';
                                                }
                                                echo '</td>';
                                                echo '<td>' . htmlspecialchars($regDate) . '</td>';
                                                echo '<td>';
                                                echo '<button class="btn btn-sm btn-outline-primary" title="View Profile" onclick="viewUser(' . $dbUser['id'] . ')">';
                                                echo '<i class="fas fa-eye"></i>';
                                                echo '</button>';
                                                if ($dbUser['user_type'] !== 'admin' || count($allUsers) > 1) {
                                                    echo '<button class="btn btn-sm btn-outline-warning" title="Edit" onclick="editUser(' . $dbUser['id'] . ')">';
                                                    echo '<i class="fas fa-edit"></i>';
                                                    echo '</button>';
                                                    if ($dbUser['user_type'] !== 'admin') {
                                                        echo '<button class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteUser(' . $dbUser['id'] . ')">';
                                                        echo '<i class="fas fa-trash"></i>';
                                                        echo '</button>';
                                                    }
                                                }
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                    } catch (PDOException $e) {
                                        error_log("Error fetching users: " . $e->getMessage());
                                        echo '<tr><td colspan="6" class="text-center text-danger">Error loading users. Please try again later.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Enter full name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role</label>
                                <select name="user_type" id="user_type" class="form-select" required>
                                    <option value="user">User</option>
                                    <option value="admin">Administrator</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm password" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" rows="2" placeholder="Enter address"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveUser()">Save User</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin.js"></script>
    <script>
    function viewUser(userId) {
        AdminUtils.showToast('Viewing user profile for user ID: ' + userId, 'info');
        // In a real application, this would open a modal or navigate to profile page
    }

    function editUser(userId) {
        AdminUtils.showToast('Editing user ID: ' + userId, 'info');
        // In a real application, this would open an edit modal
    }

    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            // In a real application, this would make an AJAX call to delete the user
            AdminUtils.showToast('User deleted successfully', 'success');
            // Reload page to show updated list
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    }
    </script>
</body>
</html>