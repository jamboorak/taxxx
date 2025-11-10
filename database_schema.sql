-- Barangay Fiscal Portal Database Schema
-- Database: barangay_fiscal_portal

CREATE DATABASE IF NOT EXISTS barangay_fiscal_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE barangay_fiscal_portal;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    user_type ENUM('admin', 'user') DEFAULT 'user',
    phone VARCHAR(20),
    address TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_user_type (user_type)
);

-- Properties Table
CREATE TABLE IF NOT EXISTS properties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    property_type VARCHAR(50) NOT NULL,
    property_address TEXT NOT NULL,
    assessed_value DECIMAL(15, 2) NOT NULL,
    lot_area DECIMAL(10, 2),
    building_area DECIMAL(10, 2),
    tax_classification VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
);

-- Tax Types Table
CREATE TABLE IF NOT EXISTS tax_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    rate DECIMAL(5, 4) DEFAULT 0.0000,
    fixed_fee DECIMAL(10, 2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tax Records Table
CREATE TABLE IF NOT EXISTS tax_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    property_id INT,
    tax_type_id INT NOT NULL,
    period_year INT NOT NULL,
    period_quarter INT,
    amount DECIMAL(15, 2) NOT NULL,
    amount_paid DECIMAL(15, 2) DEFAULT 0.00,
    status ENUM('pending', 'paid', 'overdue', 'cancelled') DEFAULT 'pending',
    due_date DATE,
    payment_date DATE,
    payment_reference VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL,
    FOREIGN KEY (tax_type_id) REFERENCES tax_types(id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_period_year (period_year)
);

-- Projects Table
CREATE TABLE IF NOT EXISTS projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    category VARCHAR(50),
    budget DECIMAL(15, 2) NOT NULL,
    allocated_budget DECIMAL(15, 2) DEFAULT 0.00,
    spent_amount DECIMAL(15, 2) DEFAULT 0.00,
    start_date DATE,
    end_date DATE,
    completion_percentage INT DEFAULT 0,
    status ENUM('planning', 'in-progress', 'completed', 'cancelled') DEFAULT 'planning',
    location VARCHAR(200),
    contractor VARCHAR(200),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_category (category)
);

-- Budget Allocation Table
CREATE TABLE IF NOT EXISTS budget_allocation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sector VARCHAR(100) NOT NULL,
    fiscal_year INT NOT NULL,
    allocated_amount DECIMAL(15, 2) NOT NULL,
    allocation_percentage DECIMAL(5, 2) NOT NULL,
    spent_amount DECIMAL(15, 2) DEFAULT 0.00,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fiscal_year (fiscal_year),
    INDEX idx_sector (sector)
);

-- Expenditures Table
CREATE TABLE IF NOT EXISTS expenditures (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    amount DECIMAL(15, 2) NOT NULL,
    status ENUM('pending', 'approved', 'paid', 'cancelled') DEFAULT 'pending',
    payment_date DATE,
    payment_reference VARCHAR(100),
    vendor VARCHAR(200),
    receipt_number VARCHAR(100),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_project_id (project_id)
);

-- Settings Table
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Default Tax Types
INSERT INTO tax_types (name, description, rate, fixed_fee, is_active) VALUES
('Real Property Tax', 'Annual tax on real estate properties', 0.0100, 0.00, TRUE),
('Business Tax', 'Tax on business operations and gross receipts', 0.0200, 0.00, TRUE),
('Community Tax', 'Resident tax for individuals and corporations', 0.0010, 50.00, TRUE),
('Professional Tax', 'Tax on professionals practicing their trade', 0.0000, 300.00, TRUE)
ON DUPLICATE KEY UPDATE name=name;

-- Insert Default Budget Allocations for 2024
INSERT INTO budget_allocation (sector, fiscal_year, allocated_amount, allocation_percentage, description) VALUES
('Health Services', 2024, 875000.00, 25.00, 'Healthcare facilities and services'),
('Education', 2024, 700000.00, 20.00, 'Educational programs and facilities'),
('Infrastructure', 2024, 1050000.00, 30.00, 'Roads, bridges, and public facilities'),
('Waste Management', 2024, 525000.00, 15.00, 'Waste collection and disposal'),
('Peace and Order', 2024, 350000.00, 10.00, 'Security and law enforcement')
ON DUPLICATE KEY UPDATE sector=sector;

-- Insert Sample Projects
INSERT INTO projects (name, description, category, budget, allocated_budget, start_date, end_date, completion_percentage, status, location, created_at) VALUES
('Barangay Health Center Renovation', 'Renovation of the main health center building', 'health', 350000.00, 350000.00, '2024-01-15', '2024-04-30', 100, 'completed', 'Barangay Center', NOW()),
('Road Rehabilitation - Purok 3', 'Comprehensive road improvement project', 'infrastructure', 520000.00, 520000.00, '2024-03-01', '2024-06-15', 75, 'in-progress', 'Purok 3', NOW()),
('Drainage System Improvement', 'Installation of new drainage system', 'infrastructure', 280000.00, 280000.00, '2024-05-10', '2024-09-20', 30, 'planning', 'Main Street', NOW()),
('Public Park Development', 'Development of community park with facilities', 'environment', 450000.00, 450000.00, '2024-04-05', '2024-07-30', 60, 'in-progress', 'Barangay Park Area', NOW())
ON DUPLICATE KEY UPDATE name=name;

-- Insert Default Settings
INSERT INTO settings (setting_key, setting_value, description) VALUES
('barangay_name', 'Barangay Del Remedio', 'Name of the barangay'),
('barangay_location', 'San Pablo City, Laguna', 'Location of the barangay'),
('contact_phone', '(049) 123-4567', 'Contact phone number'),
('contact_email', 'info@barangaydelremedio.gov.ph', 'Contact email address'),
('office_hours', 'Mon-Fri: 8:00 AM - 5:00 PM', 'Office operating hours')
ON DUPLICATE KEY UPDATE setting_key=setting_key;

-- Create default admin user (password: admin123)
-- Password hash for 'admin123'
INSERT INTO users (username, email, password, full_name, user_type, is_active) VALUES
('admin', 'admin@barangaydelremedio.gov.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', TRUE)
ON DUPLICATE KEY UPDATE username=username;


