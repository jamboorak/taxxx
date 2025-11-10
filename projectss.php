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
    <title>Projects - Barangay Del Remedio</title>
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
                <h1>Barangay Projects</h1>
                <p class="lead">Track the progress of development projects in our community</p>
            </div>

            <!-- Project Filters -->
            <div class="user-card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Filter by Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="all">All Projects</option>
                                <option value="planning">Planning</option>
                                <option value="in-progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Filter by Category</label>
                            <select class="form-select" id="categoryFilter">
                                <option value="all">All Categories</option>
                                <option value="infrastructure">Infrastructure</option>
                                <option value="health">Health Services</option>
                                <option value="education">Education</option>
                                <option value="environment">Environment</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Filter by Location</label>
                            <select class="form-select" id="locationFilter">
                                <option value="all">All Locations</option>
                                <option value="purok1">Purok 1</option>
                                <option value="purok2">Purok 2</option>
                                <option value="purok3">Purok 3</option>
                                <option value="purok4">Purok 4</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Grid -->
            <div class="row g-4" id="projectsGrid">
                <!-- Project 1 -->
                <div class="col-lg-6" data-status="in-progress" data-category="infrastructure" data-location="purok3">
                    <div class="user-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4>Road Rehabilitation - Purok 3</h4>
                                    <span class="badge bg-warning">In Progress</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">₱520,000</div>
                                    <small class="text-muted">Budget</small>
                                </div>
                            </div>
                            
                            <p class="text-muted mb-3">Improvement of road infrastructure in Purok 3 including asphalt overlay and drainage installation.</p>
                            
                            <div class="mb-3">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: 75%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small>75% Complete</small>
                                    <small>25% Remaining</small>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold">Mar 1</div>
                                        <small class="text-muted">Start</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold">Jun 15</div>
                                        <small class="text-muted">Deadline</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div>
                                        <div class="fw-bold">45 days</div>
                                        <small class="text-muted">Remaining</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="viewProjectDetails(1)">
                                    <i class="fas fa-info-circle me-2"></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project 2 -->
                <div class="col-lg-6" data-status="in-progress" data-category="environment" data-location="purok2">
                    <div class="user-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4>Public Park Development</h4>
                                    <span class="badge bg-info">In Progress</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">₱450,000</div>
                                    <small class="text-muted">Budget</small>
                                </div>
                            </div>
                            
                            <p class="text-muted mb-3">Development of community park with playground, benches, and green spaces for public use.</p>
                            
                            <div class="mb-3">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: 60%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small>60% Complete</small>
                                    <small>40% Remaining</small>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold">Apr 5</div>
                                        <small class="text-muted">Start</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold">Jul 30</div>
                                        <small class="text-muted">Deadline</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div>
                                        <div class="fw-bold">60 days</div>
                                        <small class="text-muted">Remaining</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="viewProjectDetails(2)">
                                    <i class="fas fa-info-circle me-2"></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project 3 -->
                <div class="col-lg-6" data-status="planning" data-category="infrastructure" data-location="purok1">
                    <div class="user-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4>Drainage System Improvement</h4>
                                    <span class="badge bg-secondary">Planning</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">₱280,000</div>
                                    <small class="text-muted">Budget</small>
                                </div>
                            </div>
                            
                            <p class="text-muted mb-3">Upgrade of drainage system to prevent flooding in low-lying areas of the barangay.</p>
                            
                            <div class="mb-3">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-secondary" style="width: 30%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small>30% Complete</small>
                                    <small>70% Remaining</small>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold">May 10</div>
                                        <small class="text-muted">Start</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold">Sep 20</div>
                                        <small class="text-muted">Deadline</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div>
                                        <div class="fw-bold">120 days</div>
                                        <small class="text-muted">Remaining</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="viewProjectDetails(3)">
                                    <i class="fas fa-info-circle me-2"></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project 4 -->
                <div class="col-lg-6" data-status="completed" data-category="health" data-location="purok4">
                    <div class="user-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4>Health Center Renovation</h4>
                                    <span class="badge bg-success">Completed</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">₱350,000</div>
                                    <small class="text-muted">Budget</small>
                                </div>
                            </div>
                            
                            <p class="text-muted mb-3">Renovation and modernization of barangay health center with new equipment and facilities.</p>
                            
                            <div class="mb-3">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small>100% Complete</small>
                                    <small>0% Remaining</small>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold">Jan 15</div>
                                        <small class="text-muted">Start</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold">Apr 30</div>
                                        <small class="text-muted">Completed</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div>
                                        <div class="fw-bold">-</div>
                                        <small class="text-muted">Status</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="viewProjectDetails(4)">
                                    <i class="fas fa-info-circle me-2"></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Project Details Modal -->
    <div class="modal fade" id="projectDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="projectModalTitle">Project Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="projectModalBody">
                    <!-- Project details will be loaded here -->
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
    // Project filtering
    document.addEventListener('DOMContentLoaded', function() {
        const filters = ['statusFilter', 'categoryFilter', 'locationFilter'];
        
        filters.forEach(filterId => {
            document.getElementById(filterId).addEventListener('change', filterProjects);
        });
    });

    function filterProjects() {
        const status = document.getElementById('statusFilter').value;
        const category = document.getElementById('categoryFilter').value;
        const location = document.getElementById('locationFilter').value;
        
        const projects = document.querySelectorAll('#projectsGrid > div');
        
        projects.forEach(project => {
            const projectStatus = project.getAttribute('data-status');
            const projectCategory = project.getAttribute('data-category');
            const projectLocation = project.getAttribute('data-location');
            
            const statusMatch = status === 'all' || projectStatus === status;
            const categoryMatch = category === 'all' || projectCategory === category;
            const locationMatch = location === 'all' || projectLocation === location;
            
            if (statusMatch && categoryMatch && locationMatch) {
                project.style.display = 'block';
            } else {
                project.style.display = 'none';
            }
        });
    }

    function viewProjectDetails(projectId) {
        // In a real application, this would fetch project details from an API
        const projectDetails = {
            1: {
                title: 'Road Rehabilitation - Purok 3',
                description: 'Comprehensive road improvement project including asphalt overlay, drainage installation, and sidewalk construction.',
                budget: '₱520,000',
                status: 'In Progress',
                progress: '75%',
                startDate: 'March 1, 2024',
                endDate: 'June 15, 2024',
                contractor: 'ABC Construction Company',
                updates: [
                    'May 15: Asphalt laying completed for 75% of the road',
                    'April 30: Drainage system installation in progress',
                    'March 15: Site preparation and excavation completed'
                ]
            },
            2: {
                title: 'Public Park Development',
                description: 'Development of community park with playground equipment, benches, walking paths, and green spaces.',
                budget: '₱450,000',
                status: 'In Progress',
                progress: '60%',
                startDate: 'April 5, 2024',
                endDate: 'July 30, 2024',
                contractor: 'Green Spaces Inc.',
                updates: [
                    'June 10: Playground equipment installation started',
                    'May 25: Walking path construction 50% complete',
                    'April 20: Landscaping and tree planting in progress'
                ]
            }
        };

        const project = projectDetails[projectId] || projectDetails[1];
        
        document.getElementById('projectModalTitle').textContent = project.title;
        document.getElementById('projectModalBody').innerHTML = `
            <div>
                <p>${project.description}</p>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Budget:</strong> ${project.budget}
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong> <span class="badge bg-${project.status === 'Completed' ? 'success' : project.status === 'In Progress' ? 'warning' : 'secondary'}">${project.status}</span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Start Date:</strong> ${project.startDate}
                    </div>
                    <div class="col-md-6">
                        <strong>End Date:</strong> ${project.endDate}
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Contractor:</strong> ${project.contractor}
                </div>
                
                <div class="mb-3">
                    <strong>Progress:</strong>
                    <div class="progress mt-1">
                        <div class="progress-bar bg-${project.status === 'Completed' ? 'success' : project.status === 'In Progress' ? 'warning' : 'secondary'}" style="width: ${project.progress}">${project.progress}</div>
                    </div>
                </div>
                
                <div>
                    <strong>Recent Updates:</strong>
                    <ul class="mt-2">
                        ${project.updates.map(update => `<li>${update}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('projectDetailsModal'));
        modal.show();
    }
    </script>
</body>
</html>