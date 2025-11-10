// Main JavaScript for Barangay Fiscal Portal

// Global variables
let charts = {};
let currentTaxType = 'property';
let savedCalculations = JSON.parse(localStorage.getItem('taxCalculations')) || [];

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    initializeCharts();
    initializeEventListeners();
    initializeAnimations();
    updateRealTimeData();
    checkForMessages();
    
    // Initialize tax calculator
    updateTaxCalculator();
}

// Modal Functions
function showLoginModal() {
    const modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal.show();
}

function showRegisterModal() {
    const modal = new bootstrap.Modal(document.getElementById('registerModal'));
    modal.show();
}

function closeLoginModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
    if (modal) {
        modal.hide();
    }
}

function closeRegisterModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
    if (modal) {
        modal.hide();
    }
}

// Chart initialization
function initializeCharts() {
    createRevenueChart();
    createExpenditureChart();
    createBudgetChart();
    createTaxTrendsChart();
    createSpendingChart();
}

function createRevenueChart() {
    const ctx = document.getElementById('revenueChart')?.getContext('2d');
    if (!ctx) return;

    charts.revenueChart = new Chart(ctx, {
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
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Revenue 2024',
                    font: { size: 16, weight: 'bold' }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Revenue: ₱${context.parsed.y.toLocaleString()}`;
                        }
                    }
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
}

function createExpenditureChart() {
    const ctx = document.getElementById('expenditureChart')?.getContext('2d');
    if (!ctx) return;

    charts.expenditureChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Monthly Expenditure (₱)',
                data: [420000, 480000, 450000, 470000, 460000, 380000, 400000, 420000, 450000, 430000, 460000, 480000],
                backgroundColor: 'rgba(231, 76, 60, 0.8)',
                borderColor: '#e74c3c',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Expenditure 2024',
                    font: { size: 16, weight: 'bold' }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Expenditure: ₱${context.parsed.y.toLocaleString()}`;
                        }
                    }
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
}

function createBudgetChart() {
    const ctx = document.getElementById('budgetChart')?.getContext('2d');
    if (!ctx) return;

    charts.budgetChart = new Chart(ctx, {
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
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value}% (${percentage}% of total)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
}

function createTaxTrendsChart() {
    const ctx = document.getElementById('taxTrendsChart')?.getContext('2d');
    if (!ctx) return;

    charts.taxTrendsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['2020', '2021', '2022', '2023', '2024'],
            datasets: [
                {
                    label: 'Real Property Tax',
                    data: [650000, 720000, 780000, 840000, 920000],
                    backgroundColor: 'rgba(52, 152, 219, 0.8)'
                },
                {
                    label: 'Business Tax',
                    data: [420000, 480000, 520000, 580000, 650000],
                    backgroundColor: 'rgba(46, 204, 113, 0.8)'
                },
                {
                    label: 'Community Tax',
                    data: [350000, 380000, 410000, 440000, 480000],
                    backgroundColor: 'rgba(155, 89, 182, 0.8)'
                },
                {
                    label: 'Professional Tax',
                    data: [280000, 300000, 320000, 340000, 360000],
                    backgroundColor: 'rgba(241, 196, 15, 0.8)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Tax Collection Trends (₱)',
                    font: { size: 16, weight: 'bold' }
                }
            },
            scales: {
                x: {
                    stacked: false,
                },
                y: {
                    stacked: false,
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
}

function createSpendingChart() {
    const ctx = document.getElementById('spendingChart')?.getContext('2d');
    if (!ctx) return;

    charts.spendingChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Infrastructure', 'Health Services', 'Education', 'Waste Management', 'Peace and Order', 'Others'],
            datasets: [{
                data: [30, 25, 20, 15, 7, 3],
                backgroundColor: [
                    '#3498db', '#2ecc71', '#9b59b6', '#f1c40f', '#e67e22', '#95a5a6'
                ],
                borderColor: 'white',
                borderWidth: 3,
                hoverOffset: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed}%`;
                        }
                    }
                }
            }
        }
    });
}

// Event Listeners
function initializeEventListeners() {
    // Navigation smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Window scroll events
    window.addEventListener('scroll', handleScroll);

    // Form submissions
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
    
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }

    // Password toggle
    const togglePassword = document.getElementById('togglePassword');
    const toggleRegPassword = document.getElementById('toggleRegPassword');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            togglePasswordVisibility('password', 'togglePassword');
        });
    }
    
    if (toggleRegPassword) {
        toggleRegPassword.addEventListener('click', function() {
            togglePasswordVisibility('regPassword', 'toggleRegPassword');
        });
    }

    // Password strength
    const regPassword = document.getElementById('regPassword');
    if (regPassword) {
        regPassword.addEventListener('input', checkPasswordStrength);
    }

    // Modal switching
    const loginLink = document.querySelector('a[onclick*="showLoginModal"]');
    const registerLink = document.querySelector('a[onclick*="showRegisterModal"]');
    
    if (loginLink) {
        loginLink.addEventListener('click', function(e) {
            e.preventDefault();
            closeRegisterModal();
            setTimeout(showLoginModal, 300);
        });
    }
    
    if (registerLink) {
        registerLink.addEventListener('click', function(e) {
            e.preventDefault();
            closeLoginModal();
            setTimeout(showRegisterModal, 300);
        });
    }
}

function togglePasswordVisibility(passwordFieldId, toggleButtonId) {
    const passwordField = document.getElementById(passwordFieldId);
    const toggleButton = document.getElementById(toggleButtonId);
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        passwordField.type = 'password';
        toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
    }
}

function checkPasswordStrength() {
    const password = document.getElementById('regPassword').value;
    const strengthBar = document.getElementById('passwordStrength');
    const feedback = document.getElementById('passwordFeedback');
    
    let strength = 0;
    let feedbackText = 'Password strength';
    
    if (password.length >= 8) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[^A-Za-z0-9]/.test(password)) strength += 25;
    
    strengthBar.style.width = strength + '%';
    
    if (strength === 0) {
        strengthBar.className = 'progress-bar';
        feedbackText = 'Enter a password';
    } else if (strength <= 25) {
        strengthBar.className = 'progress-bar bg-danger';
        feedbackText = 'Weak password';
    } else if (strength <= 50) {
        strengthBar.className = 'progress-bar bg-warning';
        feedbackText = 'Fair password';
    } else if (strength <= 75) {
        strengthBar.className = 'progress-bar bg-info';
        feedbackText = 'Good password';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        feedbackText = 'Strong password';
    }
    
    feedback.textContent = feedbackText;
}

// Tax Calculator Functions
function updateTaxCalculator() {
    const taxType = document.getElementById('taxType').value;
    const valueLabel = document.getElementById('taxValueLabel');
    
    switch(taxType) {
        case 'property':
            valueLabel.textContent = 'Property Value (₱)';
            break;
        case 'business':
            valueLabel.textContent = 'Annual Gross Sales (₱)';
            break;
        case 'community':
            valueLabel.textContent = 'Gross Receipts (₱)';
            break;
        case 'professional':
            valueLabel.textContent = 'Professional Category';
            break;
    }
    
    calculateTax();
}

function calculateTax() {
    const taxType = document.getElementById('taxType').value;
    const taxValue = parseFloat(document.getElementById('taxValue').value) || 0;
    
    let taxableAmount = taxValue;
    let taxRate = '0%';
    let estimatedTax = 0;
    
    switch(taxType) {
        case 'property':
            taxRate = '1%';
            estimatedTax = taxValue * 0.01;
            break;
        case 'business':
            taxRate = '2%';
            estimatedTax = taxValue * 0.02;
            break;
        case 'community':
            taxRate = '₱50 + 0.1%';
            estimatedTax = 50 + (taxValue * 0.001);
            break;
        case 'professional':
            taxRate = 'Fixed Fee';
            estimatedTax = 300; // Fixed professional tax
            taxableAmount = 'N/A';
            break;
    }
    
    // Update display
    document.getElementById('taxableAmount').textContent = 
        taxableAmount === 'N/A' ? 'N/A' : `₱${taxableAmount.toLocaleString()}`;
    document.getElementById('taxRate').textContent = taxRate;
    document.getElementById('estimatedTax').textContent = `₱${estimatedTax.toLocaleString()}`;
}

function saveTaxCalculation() {
    const taxType = document.getElementById('taxType').value;
    const taxValue = parseFloat(document.getElementById('taxValue').value) || 0;
    const estimatedTax = parseFloat(document.getElementById('estimatedTax').textContent.replace(/[^\d.]/g, '')) || 0;
    
    if (taxValue === 0 && taxType !== 'professional') {
        showToast('Please enter a valid amount before saving.', 'warning');
        return;
    }
    
    const calculation = {
        id: Date.now(),
        type: taxType,
        amount: taxValue,
        tax: estimatedTax,
        date: new Date().toLocaleString(),
        timestamp: new Date().toISOString()
    };
    
    savedCalculations.push(calculation);
    localStorage.setItem('taxCalculations', JSON.stringify(savedCalculations));
    
    showToast('Tax calculation saved successfully!', 'success');
}

// Project Management
function filterProjects(status) {
    const rows = document.querySelectorAll('#projectsTable tbody tr');
    const buttons = document.querySelectorAll('.card-actions .btn');
    
    // Update active button
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Filter rows
    rows.forEach(row => {
        if (status === 'all' || row.getAttribute('data-status') === status) {
            row.style.display = '';
            row.classList.add('animate__animated', 'animate__fadeIn');
        } else {
            row.style.display = 'none';
        }
    });
}

function viewProjectDetails(projectName) {
    showToast(`Loading details for: ${projectName}`, 'info');
}

function downloadProjectReport(projectName) {
    showToast(`Downloading report for: ${projectName}`, 'info');
    setTimeout(() => {
        showToast(`Report for ${projectName} downloaded successfully!`, 'success');
    }, 2000);
}

function viewProjectTimeline(projectName) {
    showToast(`Viewing timeline for: ${projectName}`, 'info');
}

// Budget View Toggle
function toggleBudgetView(view) {
    const chartContainer = document.getElementById('budgetChartContainer');
    const tableContainer = document.getElementById('budgetTableContainer');
    const buttons = document.querySelectorAll('.view-toggle .btn');
    
    // Update active button
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    if (view === 'chart') {
        chartContainer.classList.remove('d-none');
        tableContainer.classList.add('d-none');
    } else {
        chartContainer.classList.add('d-none');
        tableContainer.classList.remove('d-none');
    }
}

// Chart Management
function exportChart(chartName) {
    if (charts[chartName]) {
        const link = document.createElement('a');
        link.download = `${chartName}-${new Date().toISOString().split('T')[0]}.png`;
        link.href = charts[chartName].toBase64Image();
        link.click();
        showToast('Chart exported successfully!', 'success');
    }
}

function refreshChart(chartName) {
    if (charts[chartName]) {
        showToast(`Refreshing ${chartName} data...`, 'info');
        setTimeout(() => {
            showToast('Chart data updated!', 'success');
        }, 1000);
    }
}

function updateTaxTrends(range) {
    showToast(`Updating tax trends for: ${range}`, 'info');
}

function exportTimeline() {
    showToast('Exporting project timeline...', 'info');
    setTimeout(() => {
        showToast('Timeline exported successfully!', 'success');
    }, 1500);
}

// Form Handlers
function handleLogin(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const username = formData.get('username');
    const password = formData.get('password');
    
    // Simple validation
    if (!username || !password) {
        showToast('Please fill in all fields.', 'warning');
        return;
    }
    
    // Form is submitted to PHP backend
    event.target.submit();
}

function handleRegister(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const password = formData.get('password');
    const confirmPassword = formData.get('confirmPassword');
    
    // Validate passwords match
    if (password !== confirmPassword) {
        showToast('Passwords do not match!', 'warning');
        return;
    }
    
    if (password.length < 6) {
        showToast('Password must be at least 6 characters long!', 'warning');
        return;
    }
    
    // Form is submitted to PHP backend
    event.target.submit();
}

// Utility Functions
function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function handleScroll() {
    // Show/hide back to top button
    const backToTop = document.querySelector('.back-to-top');
    if (window.scrollY > 300) {
        backToTop.classList.add('show');
    } else {
        backToTop.classList.remove('show');
    }
    
    // Update navbar background
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 100) {
        navbar.style.background = 'var(--primary-color)';
        navbar.style.boxShadow = 'var(--shadow-medium)';
    } else {
        navbar.style.background = 'var(--gradient-primary)';
        navbar.style.boxShadow = 'none';
    }
}

function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    const toastId = 'toast-' + Date.now();
    
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    const toast = new bootstrap.Toast(document.getElementById(toastId));
    toast.show();
    
    // Remove toast after it's hidden
    document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

function checkForMessages() {
    // Check for PHP session messages
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('message')) {
        showToast(urlParams.get('message'), urlParams.get('type') || 'info');
    }
}

// Animation and Data
function initializeAnimations() {
    // Animate stat numbers (only for stats section, not about section)
    animateStatNumbers();
    
    // Add scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.stat-card, .dashboard-card, .feature-card').forEach(el => {
        observer.observe(el);
    });
}

function animateStatNumbers() {
    // Only target stat numbers in the stats section, not the about section
    const statNumbers = document.querySelectorAll('.stats-section .stat-number');
    
    statNumbers.forEach(stat => {
        const target = parseInt(stat.getAttribute('data-target'));
        if (isNaN(target)) return; // Skip if not a valid number
        
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            stat.textContent = Math.floor(current).toLocaleString();
        }, 16);
    });
}

// About Section Number Animation (for impact stats)
function initAboutSectionAnimation() {
    const aboutSection = document.querySelector('.about-section');
    if (!aboutSection) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateAboutStats();
                observer.unobserve(entry.target);
            }
        });
    });

    observer.observe(aboutSection);
}

function animateAboutStats() {
    const impactStats = document.querySelectorAll('.impact-stat .stat-number');
    
    impactStats.forEach(stat => {
        const text = stat.textContent.trim();
        const percentageMatch = text.match(/(\d+)%/);
        
        if (percentageMatch) {
            const target = parseInt(percentageMatch[1]);
            const duration = 1500;
            const step = target / (duration / 16);
            let current = 0;
            
            const originalText = text;
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                    stat.textContent = originalText; // Restore original text with % symbol
                } else {
                    stat.textContent = Math.floor(current) + '%';
                }
            }, 16);
        }
    });
}

function updateRealTimeData() {
    // Simulate real-time data updates
    setInterval(() => {
        // Update random stats (in a real app, this would come from an API)
        const stats = document.querySelectorAll('.stats-section .stat-number');
        stats.forEach(stat => {
            const current = parseInt(stat.textContent.replace(/[^0-9]/g, ''));
            const change = Math.floor(Math.random() * 100) - 45; // -45 to +55
            const newValue = Math.max(0, current + change);
            stat.setAttribute('data-target', newValue);
            stat.textContent = newValue.toLocaleString();
        });
    }, 30000); // Update every 30 seconds
}

// Initialize about section animation when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initAboutSectionAnimation();
});