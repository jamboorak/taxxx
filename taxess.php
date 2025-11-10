<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
requireLogin();
$user = getCurrentUser();
if (!$user) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Taxes - Barangay Del Remedio</title>
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
                <h1>My Tax Records</h1>
                <p class="lead">View and manage your tax obligations with Barangay Del Remedio</p>
            </div>

            <!-- Tax Summary -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="user-stat-card">
                        <div class="stat-icon paid">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>₱12,500</h3>
                            <p>Total Paid (2024)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="user-stat-card">
                        <div class="stat-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3>₱2,300</h3>
                            <p>Pending Balance</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="user-stat-card">
                        <div class="stat-icon compliance">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-content">
                            <h3>85%</h3>
                            <p>Compliance Rate</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tax Records -->
            <div class="user-card">
                <div class="card-header">
                    <h4><i class="fas fa-receipt me-2"></i>Tax History</h4>
                    <button class="btn btn-primary btn-sm" onclick="showPaymentModal()">
                        <i class="fas fa-credit-card me-1"></i>Make Payment
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tax Type</th>
                                    <th>Period</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Payment Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Real Property Tax</strong>
                                        <br><small>Residential Lot</small>
                                    </td>
                                    <td>Q2 2024</td>
                                    <td>₱2,500.00</td>
                                    <td>Jun 30, 2024</td>
                                    <td><span class="badge bg-success">Paid</span></td>
                                    <td>Jun 15, 2024</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-receipt"></i> Receipt
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Business Tax</strong>
                                        <br><small>Small Retail Store</small>
                                    </td>
                                    <td>Q2 2024</td>
                                    <td>₱1,800.00</td>
                                    <td>Jun 30, 2024</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="showPaymentModal()">
                                            <i class="fas fa-credit-card"></i> Pay Now
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Community Tax</strong>
                                        <br><small>Annual Fee</small>
                                    </td>
                                    <td>2024</td>
                                    <td>₱350.00</td>
                                    <td>Dec 31, 2024</td>
                                    <td><span class="badge bg-secondary">Upcoming</span></td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="fas fa-clock"></i> Pay Later
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tax Calculator -->
            <div class="user-card mt-4">
                <div class="card-header">
                    <h4><i class="fas fa-calculator me-2"></i>Tax Calculator</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form id="taxCalculatorForm">
                                <div class="mb-3">
                                    <label class="form-label">Tax Type</label>
                                    <select class="form-select" id="calcTaxType">
                                        <option value="property">Real Property Tax</option>
                                        <option value="business">Business Tax</option>
                                        <option value="community">Community Tax</option>
                                        <option value="professional">Professional Tax</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" id="calcValueLabel">Property Value (₱)</label>
                                    <input type="number" class="form-control" id="calcTaxValue" placeholder="Enter value">
                                </div>
                                <button type="button" class="btn btn-primary" onclick="calculateTax()">
                                    <i class="fas fa-calculator me-2"></i>Calculate Tax
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div class="calculation-result p-3 bg-light rounded">
                                <h5>Estimated Tax</h5>
                                <div id="calcResult" class="mt-3">
                                    <p class="text-muted">Enter values to calculate your tax obligation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Make Tax Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm">
                        <div class="mb-3">
                            <label class="form-label">Select Tax</label>
                            <select class="form-select">
                                <option>Business Tax - Q2 2024 - ₱1,800.00</option>
                                <option>Community Tax - 2024 - ₱350.00</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select">
                                <option>GCash</option>
                                <option>PayMaya</option>
                                <option>Bank Transfer</option>
                                <option>Credit Card</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="text" class="form-control" value="₱1,800.00" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="processPayment()">Proceed to Payment</button>
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
    function showPaymentModal() {
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
    }

    function processPayment() {
        alert('Redirecting to payment gateway...');
        const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
        modal.hide();
    }

    function calculateTax() {
        const type = document.getElementById('calcTaxType').value;
        const value = parseFloat(document.getElementById('calcTaxValue').value) || 0;
        let tax = 0;

        switch(type) {
            case 'property':
                tax = value * 0.01;
                break;
            case 'business':
                tax = value * 0.02;
                break;
            case 'community':
                tax = 50 + (value * 0.001);
                break;
            case 'professional':
                tax = 300;
                break;
        }

        document.getElementById('calcResult').innerHTML = `
            <div class="alert alert-info">
                <h4>₱${tax.toLocaleString()}</h4>
                <p class="mb-0">Estimated tax amount</p>
            </div>
        `;
    }

    // Update label based on tax type
    document.getElementById('calcTaxType').addEventListener('change', function() {
        const label = document.getElementById('calcValueLabel');
        switch(this.value) {
            case 'property':
                label.textContent = 'Property Value (₱)';
                break;
            case 'business':
                label.textContent = 'Annual Gross Sales (₱)';
                break;
            case 'community':
                label.textContent = 'Gross Receipts (₱)';
                break;
            case 'professional':
                label.textContent = 'Professional Category';
                break;
        }
    });
    </script>
</body>
</html>