// Admin Dashboard JavaScript
class AdminDashboard {
    constructor() {
        this.init();
    }

    init() {
        this.initializeCharts();
        this.initializeEventListeners();
        this.initializeSidebar();
    }

    initializeCharts() {
        this.createRevenueExpenditureChart();
        this.createBudgetChart();
    }

    createRevenueExpenditureChart() {
        const ctx = document.getElementById('revenueExpenditureChart')?.getContext('2d');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Revenue (₱)',
                        data: [450000, 520000, 480000, 510000, 490000, 397350, 420000, 460000, 500000, 480000, 520000, 550000],
                        borderColor: '#27ae60',
                        backgroundColor: 'rgba(39, 174, 96, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Expenditure (₱)',
                        data: [420000, 480000, 450000, 470000, 460000, 380000, 400000, 420000, 450000, 430000, 460000, 480000],
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Revenue vs Expenditure 2024',
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
    }

    createBudgetChart() {
        const ctx = document.getElementById('budgetChart')?.getContext('2d');
        if (!ctx) return;

        new Chart(ctx, {
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
                        position: 'bottom'
                    }
                },
                cutout: '60%'
            }
        });
    }

    initializeEventListeners() {
        // Notification bell
        const notificationBtn = document.querySelector('.btn-notification');
        if (notificationBtn) {
            notificationBtn.addEventListener('click', () => this.showNotifications());
        }

        // Profile button
        const profileBtn = document.querySelector('.btn-profile');
        if (profileBtn) {
            profileBtn.addEventListener('click', () => {
                window.location.href = 'profile.php';
            });
        }

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    initializeSidebar() {
        // Add active class to current page
        const currentPage = window.location.pathname.split('/').pop();
        const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPage || href.includes(currentPage)) {
                link.classList.add('active');
                link.parentElement.classList.add('active');
            }
        });

        // Sidebar toggle functionality for all screen sizes
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.admin-sidebar');
        const mainContent = document.querySelector('.admin-main');
        const overlay = this.createOverlay();

        if (sidebarToggle && sidebar) {
            // Check if sidebar should be hidden by default on mobile
            const isMobile = window.innerWidth <= 768;
            
            // Only hide sidebar on mobile by default, desktop shows it
            if (isMobile) {
                sidebar.classList.add('hidden');
            } else {
                // Desktop: sidebar visible by default
                sidebar.classList.remove('hidden');
                if (mainContent) mainContent.classList.remove('sidebar-hidden');
            }

            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = sidebar.classList.contains('hidden');
                
                if (isMobile) {
                    // Mobile: show/hide with overlay
                    if (isHidden) {
                        sidebar.classList.remove('hidden');
                        overlay.style.display = 'block';
                        document.body.style.overflow = 'hidden';
                    } else {
                        sidebar.classList.add('hidden');
                        overlay.style.display = 'none';
                        document.body.style.overflow = '';
                    }
                } else {
                    // Desktop: toggle sidebar and adjust main content
                    if (isHidden) {
                        sidebar.classList.remove('hidden');
                        if (mainContent) mainContent.classList.remove('sidebar-hidden');
                        // Animate navigation items appearing
                        this.animateNavItems('in');
                    } else {
                        sidebar.classList.add('hidden');
                        if (mainContent) mainContent.classList.add('sidebar-hidden');
                        this.animateNavItems('out');
                    }
                }
            });

            // Close sidebar when clicking overlay (mobile)
            overlay.addEventListener('click', () => {
                sidebar.classList.add('hidden');
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                const isMobileNow = window.innerWidth <= 768;
                if (isMobileNow && !sidebar.classList.contains('hidden')) {
                    sidebar.classList.add('hidden');
                    overlay.style.display = 'none';
                    if (mainContent) mainContent.classList.remove('sidebar-hidden');
                } else if (!isMobileNow) {
                    overlay.style.display = 'none';
                }
            });

            // Close sidebar when clicking nav links on mobile
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.add('hidden');
                        overlay.style.display = 'none';
                        document.body.style.overflow = '';
                    }
                });
            });
        }
    }

    animateNavItems(direction) {
        const navItems = document.querySelectorAll('.sidebar-nav .nav-item');
        navItems.forEach((item, index) => {
            if (direction === 'in') {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 50);
            } else {
                item.style.transition = 'all 0.2s ease';
                item.style.opacity = '0.5';
            }
        });
    }

    createOverlay() {
        let overlay = document.getElementById('sidebar-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'sidebar-overlay';
            overlay.style.cssText = `
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1000;
                transition: opacity 0.3s ease;
            `;
            document.body.appendChild(overlay);
        }
        return overlay;
    }

    showNotifications() {
        // Create notifications dropdown
        let dropdown = document.getElementById('notificationsDropdown');
        if (!dropdown) {
            dropdown = document.createElement('div');
            dropdown.id = 'notificationsDropdown';
            dropdown.style.cssText = `
                position: absolute;
                top: 100%;
                right: 0;
                margin-top: 10px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                min-width: 300px;
                max-height: 400px;
                overflow-y: auto;
                z-index: 1000;
                display: none;
            `;
            
            dropdown.innerHTML = `
                <div style="padding: 1rem; border-bottom: 1px solid #e9ecef;">
                    <h6 style="margin: 0; font-weight: 600;">Notifications</h6>
                </div>
                <div style="padding: 0.5rem;">
                    <div style="padding: 0.75rem; border-bottom: 1px solid #f0f0f0;">
                        <small style="color: #6c757d;">New tax payment received</small>
                        <br><small style="color: #adb5bd;">2 minutes ago</small>
                    </div>
                    <div style="padding: 0.75rem; border-bottom: 1px solid #f0f0f0;">
                        <small style="color: #6c757d;">Project update available</small>
                        <br><small style="color: #adb5bd;">1 hour ago</small>
                    </div>
                    <div style="padding: 0.75rem;">
                        <small style="color: #6c757d;">New user registration</small>
                        <br><small style="color: #adb5bd;">3 hours ago</small>
                    </div>
                </div>
            `;
            
            const notificationBtn = document.querySelector('.btn-notification');
            if (notificationBtn) {
                notificationBtn.style.position = 'relative';
                notificationBtn.parentElement.appendChild(dropdown);
            }
        }
        
        // Toggle dropdown
        if (dropdown.style.display === 'none' || !dropdown.style.display) {
            dropdown.style.display = 'block';
            // Close when clicking outside
            setTimeout(() => {
                document.addEventListener('click', function closeDropdown(e) {
                    if (!dropdown.contains(e.target) && e.target !== document.querySelector('.btn-notification')) {
                        dropdown.style.display = 'none';
                        document.removeEventListener('click', closeDropdown);
                    }
                });
            }, 100);
        } else {
            dropdown.style.display = 'none';
        }
    }
}

// Utility functions for admin
class AdminUtils {
    static showToast(message, type = 'info') {
        // Create toast notification container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
            document.body.appendChild(toastContainer);
        }

        // Create toast notification
        const toast = document.createElement('div');
        const alertType = type === 'danger' ? 'danger' : type === 'success' ? 'success' : 'info';
        toast.className = `alert alert-${alertType} alert-dismissible fade show`;
        toast.style.cssText = 'min-width: 300px; margin-bottom: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 150);
        }, 5000);
    }

    static confirmAction(message) {
        return confirm(message);
    }

    static formatCurrency(amount) {
        return '₱' + parseFloat(amount).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
}

// Initialize admin dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new AdminDashboard();
});

// Global admin functions
function showAddProjectModal() {
    const modal = new bootstrap.Modal(document.getElementById('addProjectModal'));
    modal.show();
}

function saveProject() {
    AdminUtils.showToast('Project saved successfully!', 'success');
    const modal = bootstrap.Modal.getInstance(document.getElementById('addProjectModal'));
    modal.hide();
}

function showAddTaxModal() {
    const modal = new bootstrap.Modal(document.getElementById('addTaxModal'));
    modal.show();
}

function saveTaxRecord() {
    AdminUtils.showToast('Tax record saved successfully!', 'success');
    const modal = bootstrap.Modal.getInstance(document.getElementById('addTaxModal'));
    modal.hide();
}

function showAddUserModal() {
    const modal = new bootstrap.Modal(document.getElementById('addUserModal'));
    modal.show();
}

function saveUser() {
    const form = document.getElementById('addUserForm');
    
    // Get form values using IDs
    const fullName = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const role = document.getElementById('user_type').value;
    const address = document.getElementById('address').value.trim();

    // Validation
    if (!fullName || !email || !username || !password || !confirmPassword) {
        AdminUtils.showToast('Please fill in all required fields', 'danger');
        return;
    }

    if (password !== confirmPassword) {
        AdminUtils.showToast('Passwords do not match', 'danger');
        return;
    }

    if (password.length < 6) {
        AdminUtils.showToast('Password must be at least 6 characters long', 'danger');
        return;
    }

    // Send AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'add_user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                AdminUtils.showToast('User added successfully!', 'success');
                form.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                modal.hide();
                // Reload page after 1 second to show new user
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                AdminUtils.showToast(response.message || 'Error adding user', 'danger');
            }
        } else {
            AdminUtils.showToast('Error connecting to server', 'danger');
        }
    };

    xhr.onerror = function() {
        AdminUtils.showToast('Network error occurred', 'danger');
    };

    // Send data
    const data = `full_name=${encodeURIComponent(fullName)}&email=${encodeURIComponent(email)}&username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&user_type=${encodeURIComponent(role)}&address=${encodeURIComponent(address)}`;
    xhr.send(data);
}

function generateReport() {
    const reportType = document.getElementById('reportType').value;
    const dateRange = document.getElementById('dateRange').value;
    const format = document.getElementById('reportFormat').value;
    
    AdminUtils.showToast(`Generating ${reportType} report for ${dateRange} in ${format.toUpperCase()} format...`, 'info');
}