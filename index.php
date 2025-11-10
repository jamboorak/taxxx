<?php 
session_start();
// Include config file with database connection and data
require_once 'config.php';
require_once 'auth.php';

// Get dashboard stats
$stats = getDashboardStats();
$total_taxes = $stats['total_taxes'] ?? 0;
$total_budget = $stats['total_budget'] ?? 0;
$completed_projects = $stats['completed_projects'] ?? 0;
$tax_compliance = $stats['tax_compliance'] ?? 0;
$budget_allocations = $stats['budget_allocations'] ?? []; // Ensure it's always an array
$projects = $stats['projects'] ?? [];
$top_expenditures = $stats['top_expenditures'] ?? [];
$project_timeline = $stats['project_timeline'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Del Remedio - Fiscal Transparency Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-landmark me-2"></i>
                <span class="brand-text">Barangay Del Remedio</span>
                <small class="brand-subtitle">Fiscal Portal</small>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#dashboard">
                            <i class="fas fa-chart-bar me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#taxes">
                            <i class="fas fa-receipt me-1"></i>Taxes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#spending">
                            <i class="fas fa-money-bill-wave me-1"></i>Spending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                    </li>
                    <li class="nav-item">
                            <?php if(isset($_SESSION['user_id'])): 
                            if($_SESSION['user_type'] === 'admin'): ?>
                                <a href="dashboard.php" class="btn btn-outline-light">
                                    <i class="fas fa-cog me-1"></i>Admin Panel
                                </a>
                            <?php else: ?>
                                <a href="dashboardd.php" class="btn btn-outline-light">
                                    <i class="fas fa-user me-1"></i>My Account
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <button class="btn btn-outline-light" onclick="showLoginModal()">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </button>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    // Clear message after displaying
    $message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
    $message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';
    if(isset($_SESSION['message'])) {
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>

    <!-- Message Toast -->
    <?php if($message): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div class="toast show align-items-center text-white bg-<?php echo $message_type === 'error' ? 'danger' : $message_type; ?> border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : ($message_type === 'error' ? 'exclamation-triangle' : 'info-circle'); ?> me-2"></i>
                    <?php echo $message; ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInUp">
                        Transparent Governance for a <span class="text-highlight">Better Community</span>
                    </h1>
                    <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                        Track local taxes and public spending in Barangay Del Remedio, San Pablo City. 
                        Promoting accountability and citizen engagement through open data.
                    </p>
                    <div class="hero-buttons animate__animated animate__fadeInUp animate__delay-2s">
                        <button class="btn btn-primary btn-lg me-3" onclick="scrollToSection('dashboard')">
                            <i class="fas fa-chart-line me-2"></i>View Dashboard
                        </button>
                        <button class="btn btn-outline-light btn-lg" onclick="showRegisterModal()">
                            <i class="fas fa-user-plus me-2"></i>Get Started
                        </button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-visual animate__animated animate__fadeInRight">
                        <div class="floating-card card-1">
                            <i class="fas fa-chart-pie"></i>
                            <span>Budget Analytics</span>
                        </div>
                        <div class="floating-card card-2">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Tax Reports</span>
                        </div>
                        <div class="floating-card card-3">
                            <i class="fas fa-project-diagram"></i>
                            <span>Projects</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-indicator">
            <div class="mouse">
                <div class="wheel"></div>
            </div>
            <div class="arrow"></div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="py-5 stats-section">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-3">
                    <div class="stat-card animate__animated">
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-number" data-target="<?php echo $total_taxes; ?>">0</div>
                        <p>Total Tax Collection (2024)</p>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> 12.5%
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card animate__animated">
                        <div class="stat-icon">
                            <i class="fas fa-piggy-bank"></i>
                        </div>
                        <div class="stat-number" data-target="<?php echo $total_budget; ?>">0</div>
                        <p>Annual Budget</p>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> 8.3%
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card animate__animated">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-number" data-target="<?php echo $completed_projects; ?>">0</div>
                        <p>Completed Projects</p>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> 15.2%
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card animate__animated">
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-number" data-target="<?php echo $tax_compliance; ?>">0</div>
                        <p>Tax Compliance Rate</p>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> 5.7%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Section -->
    <section id="dashboard" class="py-5 dashboard-section">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="display-5 fw-bold">Fiscal Transparency Dashboard</h2>
                <p class="lead">Real-time insights into barangay finances and project progress</p>
            </div>
            
            <!-- Budget Allocation -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h4><i class="fas fa-chart-pie me-2"></i>Budget Allocation by Sector</h4>
                    <div class="view-toggle">
                        <button class="btn btn-sm btn-outline-primary active" data-view="chart" onclick="toggleBudgetView('chart')">
                            <i class="fas fa-chart-pie"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary" data-view="table" onclick="toggleBudgetView('table')">
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-container" id="budgetChartContainer">
                            <canvas id="budgetChart" height="300"></canvas>
                        </div>
                        <div class="table-container d-none" id="budgetTableContainer">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sector</th>
                                        <th>Allocation %</th>
                                        <th>Allocated Amount</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($budget_allocations as $allocation): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($allocation['sector']); ?></td>
                                        <td><?php echo $allocation['allocation_percentage']; ?>%</td>
                                        <td>₱<?php echo number_format($allocation['allocated_amount'], 2); ?></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: <?php echo $allocation['allocation_percentage']; ?>%">
                                                    <?php echo $allocation['allocation_percentage']; ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="allocation-details">
                            <h5>Allocation Details</h5>
                            <div class="allocation-list">
                                <?php foreach($budget_allocations as $allocation): ?>
                                <div class="allocation-item">
                                    <div class="allocation-info">
                                        <span class="sector-name"><?php echo htmlspecialchars($allocation['sector']); ?></span>
                                        <span class="allocation-amount">₱<?php echo number_format($allocation['allocated_amount'], 2); ?></span>
                                    </div>
                                    <div class="allocation-bar">
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?php echo $allocation['allocation_percentage']; ?>%">
                                                <span class="percentage"><?php echo $allocation['allocation_percentage']; ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Projects -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h4><i class="fas fa-list-alt me-2"></i>Recent Projects</h4>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline-primary active" onclick="filterProjects('all')">All</button>
                        <button class="btn btn-sm btn-outline-primary" onclick="filterProjects('completed')">Completed</button>
                        <button class="btn btn-sm btn-outline-primary" onclick="filterProjects('in-progress')">In Progress</button>
                        <button class="btn btn-sm btn-outline-primary" onclick="filterProjects('planning')">Planning</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="projectsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Project Name</th>
                                <th>Budget</th>
                                <th>Status</th>
                                <th>Completion</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($projects as $project): ?>
                            <tr data-status="<?php echo $project['status']; ?>">
                                <td>
                                    <div class="project-info">
                                        <strong><?php echo htmlspecialchars($project['project_name']); ?></strong>
                                        <small class="text-muted">Last updated: <?php echo date('M j, Y'); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="budget-amount">
                                        ₱<?php echo number_format($project['budget'], 2); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $status_class = [
                                        'planning' => 'bg-info',
                                        'in-progress' => 'bg-warning',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger'
                                    ];
                                    ?>
                                    <span class="status-badge <?php echo $status_class[$project['status']]; ?>">
                                        <i class="fas fa-circle me-1"></i>
                                        <?php echo ucfirst($project['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="completion-progress">
                                        <div class="progress">
                                            <div class="progress-bar 
                                                <?php 
                                                if($project['completion_percentage'] == 100) echo 'bg-success';
                                                elseif($project['completion_percentage'] >= 50) echo 'bg-warning';
                                                else echo 'bg-info';
                                                ?>" 
                                                style="width: <?php echo $project['completion_percentage']; ?>%">
                                            </div>
                                        </div>
                                        <div class="completion-text">
                                            <span><?php echo $project['completion_percentage']; ?>%</span>
                                            <small><?php echo $project['completion_percentage'] == 100 ? 'Completed' : 'In Progress'; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewProjectDetails('<?php echo $project['project_name']; ?>')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="downloadProjectReport('<?php echo $project['project_name']; ?>')">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Tax Information Section -->
    <section id="taxes" class="py-5 taxes-section">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="display-5 fw-bold">Tax Information & Services</h2>
                <p class="lead">Comprehensive tax information and online services for residents</p>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="feature-card h-100">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <h5 class="card-title">Tax Types & Information</h5>
                            <p class="card-text">Learn about the different types of taxes collected in Barangay Del Remedio and their purposes.</p>
                            <div class="tax-types">
                                <div class="tax-type-item">
                                    <i class="fas fa-home"></i>
                                    <div>
                                        <h6>Real Property Tax</h6>
                                        <p>Annual tax on real estate properties</p>
                                        <small class="text-muted">Rate: 1% of assessed value</small>
                                    </div>
                                </div>
                                <div class="tax-type-item">
                                    <i class="fas fa-store"></i>
                                    <div>
                                        <h6>Business Tax</h6>
                                        <p>Tax on business operations and gross receipts</p>
                                        <small class="text-muted">Rate: 2% of gross sales</small>
                                    </div>
                                </div>
                                <div class="tax-type-item">
                                    <i class="fas fa-users"></i>
                                    <div>
                                        <h6>Community Tax</h6>
                                        <p>Resident tax for individuals and corporations</p>
                                        <small class="text-muted">Fixed fee + 0.1% of gross receipts</small>
                                    </div>
                                </div>
                                <div class="tax-type-item">
                                    <i class="fas fa-user-tie"></i>
                                    <div>
                                        <h6>Professional Tax</h6>
                                        <p>Tax on professionals practicing their trade</p>
                                        <small class="text-muted">Fixed annual fee: ₱300</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="feature-card h-100">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <h5 class="card-title">Tax Calculator</h5>
                            <p class="card-text">Estimate your tax obligations using our intelligent calculator.</p>
                            <form id="taxCalculator" class="tax-calculator">
                                <div class="mb-3">
                                    <label for="taxType" class="form-label">Tax Type</label>
                                    <select class="form-select" id="taxType" onchange="updateTaxCalculator()">
                                        <option value="property">Real Property Tax</option>
                                        <option value="business">Business Tax</option>
                                        <option value="community">Community Tax</option>
                                        <option value="professional">Professional Tax</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="taxValue" class="form-label" id="taxValueLabel">Property Value (₱)</label>
                                    <input type="number" class="form-control" id="taxValue" placeholder="Enter value" oninput="calculateTax()">
                                </div>
                                <div class="calculation-result" id="calculationResult">
                                    <div class="result-header">
                                        <h6>Estimated Tax Calculation</h6>
                                    </div>
                                    <div class="result-details">
                                        <div class="result-item">
                                            <span>Taxable Amount:</span>
                                            <span id="taxableAmount">₱0.00</span>
                                        </div>
                                        <div class="result-item">
                                            <span>Tax Rate:</span>
                                            <span id="taxRate">0%</span>
                                        </div>
                                        <div class="result-item total">
                                            <span>Estimated Tax:</span>
                                            <span id="estimatedTax">₱0.00</span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary w-100 mt-3" onclick="saveTaxCalculation()">
                                    <i class="fas fa-save me-2"></i>Save Calculation
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tax Trends -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h4><i class="fas fa-chart-line me-2"></i>Tax Collection Trends</h4>
                    <div class="time-filter">
                        <select class="form-select form-select-sm" onchange="updateTaxTrends(this.value)">
                            <option value="5years">Last 5 Years</option>
                            <option value="3years">Last 3 Years</option>
                            <option value="1year">Last Year</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="taxTrendsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </section>

    <!-- Public Spending Section -->
    <section id="spending" class="py-5 spending-section">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="display-5 fw-bold">Public Spending & Projects</h2>
                <p class="lead">Transparent tracking of public funds and project expenditures</p>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-7">
                    <div class="dashboard-card h-100">
                        <div class="card-header">
                            <h4><i class="fas fa-chart-pie me-2"></i>Expenditure by Category</h4>
                        </div>
                        <div class="chart-container">
                            <canvas id="spendingChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="dashboard-card h-100">
                        <div class="card-header">
                            <h4><i class="fas fa-list-ol me-2"></i>Top Expenditures</h4>
                        </div>
                        <div class="expenditure-list">
                            <?php foreach($top_expenditures as $index => $expense): ?>
                            <div class="expenditure-item">
                                <div class="expenditure-rank">#<?php echo $index + 1; ?></div>
                                <div class="expenditure-info">
                                    <h6><?php echo htmlspecialchars($expense['category']); ?></h6>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: <?php echo ($expense['amount'] / $total_budget) * 100; ?>%"></div>
                                    </div>
                                </div>
                                <div class="expenditure-amount">
                                    ₱<?php echo number_format($expense['amount'], 2); ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Project Timeline -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h4><i class="fas fa-calendar-alt me-2"></i>Project Timeline</h4>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="exportTimeline()">
                            <i class="fas fa-download me-1"></i>Export Timeline
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Project</th>
                                <th>Timeline</th>
                                <th>Budget</th>
                                <th>Status</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($project_timeline as $index => $project): ?>
                            <tr>
                                <td>
                                    <div class="project-info">
                                        <strong><?php echo htmlspecialchars($project['project_name']); ?></strong>
                                        <small class="text-muted">ID: PRJ-<?php echo str_pad($index + 1, 3, '0', STR_PAD_LEFT); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="timeline-info">
                                        <div class="timeline-dates">
                                            <span class="start-date"><?php echo date('M j, Y', strtotime($project['start_date'])); ?></span>
                                            <i class="fas fa-arrow-right mx-2"></i>
                                            <span class="end-date"><?php echo date('M j, Y', strtotime($project['end_date'])); ?></span>
                                        </div>
                                        <div class="timeline-progress">
                                            <?php
                                            $start = strtotime($project['start_date']);
                                            $end = strtotime($project['end_date']);
                                            $today = time();
                                            $total = $end - $start;
                                            $elapsed = $today - $start;
                                            $percentage = $total > 0 ? min(100, max(0, ($elapsed / $total) * 100)) : 0;
                                            ?>
                                            <div class="progress">
                                                <div class="progress-bar 
                                                    <?php echo $percentage >= 100 ? 'bg-success' : 'bg-primary'; ?>" 
                                                    style="width: <?php echo $percentage; ?>%">
                                                </div>
                                            </div>
                                            <small><?php echo round($percentage); ?>% of timeline elapsed</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="budget-info">
                                        <span class="budget-amount">₱<?php echo number_format($project['budget'], 2); ?></span>
                                        <small class="text-muted">Allocated</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge 
                                        <?php 
                                        $status_class = [
                                            'planning' => 'bg-info',
                                            'in-progress' => 'bg-warning',
                                            'completed' => 'bg-success',
                                            'cancelled' => 'bg-danger'
                                        ];
                                        echo $status_class[$project['status']];
                                        ?>
                                    ">
                                        <i class="fas fa-circle me-1"></i>
                                        <?php echo ucfirst($project['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewProjectTimeline('<?php echo $project['project_name']; ?>')">
                                        <i class="fas fa-chart-line me-1"></i>View Progress
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 about-section">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="display-5 fw-bold">About the Portal</h2>
                <p class="lead">Promoting transparency, accountability, and citizen engagement</p>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="feature-card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h5 class="card-title">Our Mission</h5>
                            <p class="card-text">To enhance transparency, accountability, and public trust in Barangay Del Remedio by providing real-time access to fiscal information through an accessible digital platform.</p>
                            <div class="impact-stats">
                                <div class="impact-stat">
                                    <div class="stat-number">15%</div>
                                    <div class="stat-label">Increase in Tax Compliance</div>
                                </div>
                                <div class="impact-stat">
                                    <div class="stat-number">22%</div>
                                    <div class="stat-label">Improvement in Citizen Satisfaction</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="feature-card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h5 class="card-title">Impact & Achievements</h5>
                            <p class="card-text">Since implementation, we've achieved significant milestones in governance transparency and public service delivery.</p>
                            <div class="achievements">
                                <div class="achievement-item">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <span>100% Budget Transparency</span>
                                </div>
                                <div class="achievement-item">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <span>Real-time Project Tracking</span>
                                </div>
                                <div class="achievement-item">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <span>Digital Tax Services</span>
                                </div>
                                <div class="achievement-item">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <span>Citizen Feedback Integration</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="value-card text-center h-100">
                        <div class="value-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="card-title">Accountability</h5>
                        <p class="card-text">Ensuring public funds are used appropriately and effectively for community development through rigorous tracking and reporting.</p>
                        <div class="value-features">
                            <span class="badge bg-light text-dark">Real-time Auditing</span>
                            <span class="badge bg-light text-dark">Expense Tracking</span>
                            <span class="badge bg-light text-dark">Compliance Monitoring</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="value-card text-center h-100">
                        <div class="value-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h5 class="card-title">Transparency</h5>
                        <p class="card-text">Making financial data accessible to all residents in an understandable format, fostering informed community participation.</p>
                        <div class="value-features">
                            <span class="badge bg-light text-dark">Open Data</span>
                            <span class="badge bg-light text-dark">Visual Reports</span>
                            <span class="badge bg-light text-dark">Public Access</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="value-card text-center h-100">
                        <div class="value-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h5 class="card-title">Trust</h5>
                        <p class="card-text">Building confidence between citizens and local government through open communication and demonstrable results.</p>
                        <div class="value-features">
                            <span class="badge bg-light text-dark">Community Engagement</span>
                            <span class="badge bg-light text-dark">Regular Updates</span>
                            <span class="badge bg-light text-dark">Feedback Loop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-brand">
                    <i class="fas fa-landmark me-2"></i>
                    <h5>Barangay Del Remedio Fiscal Portal</h5>
                </div>
                <p class="footer-description">Promoting transparency and accountability in local governance through technology and community engagement.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-2">
                <h5>Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="#home"><i class="fas fa-chevron-right me-2"></i>Home</a></li>
                    <li><a href="#dashboard"><i class="fas fa-chevron-right me-2"></i>Dashboard</a></li>
                    <li><a href="#taxes"><i class="fas fa-chevron-right me-2"></i>Tax Information</a></li>
                    <li><a href="#spending"><i class="fas fa-chevron-right me-2"></i>Public Spending</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h5>Services</h5>
                <ul class="footer-links">
                    <li><a href="#taxes" onclick="scrollToSection('taxes')"><i class="fas fa-chevron-right me-2"></i>Tax Calculator</a></li>
                    <li><a href="#spending" onclick="scrollToSection('spending')"><i class="fas fa-chevron-right me-2"></i>Project Tracking</a></li>
                    <li><a href="#dashboard" onclick="scrollToSection('dashboard')"><i class="fas fa-chevron-right me-2"></i>Budget Reports</a></li>
                    <li><a href="#" onclick="showToast('Document downloads feature coming soon!', 'info')"><i class="fas fa-chevron-right me-2"></i>Document Downloads</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h5>Contact Us</h5>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Barangay Del Remedio, San Pablo City, Laguna</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>(049) 123-4567</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@barangaydelremedio.gov.ph</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <span>Mon-Fri: 8:00 AM - 5:00 PM</span>
                    </div>
                </div>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>&copy; 2024 Barangay Del Remedio Fiscal Transparency Portal. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-links">
                        <a href="#" onclick="showToast('Privacy Policy page coming soon!', 'info')">Privacy Policy</a>
                        <a href="#" onclick="showToast('Terms of Service page coming soon!', 'info')">Terms of Service</a>
                        <a href="#" onclick="showToast('Accessibility information coming soon!', 'info')">Accessibility</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-sign-in-alt me-2"></i>Login to Your Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username or Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username or email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                            <a href="#" class="float-end">Forgot password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </form>
                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="#" onclick="showRegisterModal()">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Create New Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="registerForm" action="register.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter your full name" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="regUsername" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                <input type="text" class="form-control" id="regUsername" name="username" placeholder="Choose a username" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="regPassword" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="regPassword" name="password" placeholder="Create a password" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleRegPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength mt-2">
                                <div class="progress">
                                    <div class="progress-bar" id="passwordStrength" style="width: 0%"></div>
                                </div>
                                <small id="passwordFeedback">Password strength</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agreeTerms" required>
                            <label class="form-check-label" for="agreeTerms">
                                I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </form>
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="#" onclick="showLoginModal()">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button class="btn btn-primary back-to-top" onclick="scrollToTop()">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>