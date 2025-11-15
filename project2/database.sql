-- =====================================================
-- TechHive Database Setup Script
-- Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
-- Created: October 2025
-- Purpose: Create database and tables for Project Part 2
-- =====================================================

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS techhive_db;
USE techhive_db;

-- =====================================================
-- 1. Jobs Table - Store job descriptions
-- =====================================================
DROP TABLE IF EXISTS jobs;
CREATE TABLE jobs (
    job_reference VARCHAR(10) PRIMARY KEY,
    job_title VARCHAR(100) NOT NULL,
    job_type VARCHAR(50),
    location VARCHAR(100),
    salary_range VARCHAR(50),
    reports_to VARCHAR(100),
    job_overview TEXT,
    key_responsibilities TEXT,
    essential_requirements TEXT,
    preferable_skills TEXT,
    posted_date DATE DEFAULT CURRENT_DATE,
    status ENUM('Active', 'Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert job data
INSERT INTO jobs (job_reference, job_title, job_type, location, salary_range, reports_to, job_overview, key_responsibilities, essential_requirements, preferable_skills) VALUES
('TVFED01', 'Front-End Developer', 'Full-time', 'Melbourne, VIC', '$85,000 - $110,000', 'Lead Front-End Developer', 
'We are seeking a skilled Front-End Developer to join our dynamic team. You will be responsible for creating responsive, accessible, and performant web applications that delight our users.',
'• Develop responsive web interfaces using HTML5, CSS3, and JavaScript
• Collaborate with UX designers to implement design systems
• Optimize applications for maximum speed and scalability
• Ensure cross-browser compatibility and accessibility compliance
• Participate in code reviews and team knowledge sharing
• Maintain and improve existing codebases',
'• 3+ years professional front-end development experience
• Proficiency in HTML5, CSS3, and vanilla JavaScript
• Experience with modern CSS frameworks (Tailwind CSS preferred)
• Strong understanding of responsive design principles
• Knowledge of web accessibility standards (WCAG 2.1)
• Experience with version control (Git)',
'• Experience with React.js or Vue.js frameworks
• Knowledge of TypeScript
• Familiarity with testing frameworks (Jest, Cypress)
• Understanding of CI/CD pipelines
• Experience with design tools (Figma, Sketch)'),

('TVUXD01', 'Senior UX Designer', 'Full-time', 'Melbourne, VIC', '$95,000 - $125,000', 'Head of Design',
'Join our design team as a Senior UX Designer to create intuitive and engaging user experiences for our digital products. You will lead design initiatives and mentor junior designers.',
'• Conduct user research and usability testing
• Create user flows, wireframes, and prototypes
• Develop and maintain design systems
• Collaborate with product managers and developers
• Present design concepts to stakeholders
• Mentor junior design team members',
'• 5+ years experience in UX/UI design
• Strong portfolio demonstrating UX process
• Proficiency in design tools (Figma, Adobe Creative Suite)
• Experience with user research methodologies
• Understanding of front-end development constraints
• Excellent communication and presentation skills',
'• Experience designing for enterprise applications
• Knowledge of motion design principles
• Experience with accessibility in design
• Prototyping with Framer or Principle
• Experience in agile development environments');

-- =====================================================
-- 2. EOI (Expressions of Interest) Table
-- =====================================================
DROP TABLE IF EXISTS eoi;
CREATE TABLE eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    job_reference VARCHAR(10) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    date_of_birth DATE,
    gender ENUM('male', 'female', 'non-binary', 'prefer-not-to-say'),
    street_address VARCHAR(40) NOT NULL,
    suburb VARCHAR(40) NOT NULL,
    state ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
    postcode CHAR(4) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(12) NOT NULL,
    skill1 VARCHAR(50),
    skill2 VARCHAR(50),
    skill3 VARCHAR(50),
    skill4 VARCHAR(50),
    skill5 VARCHAR(50),
    skill6 VARCHAR(50),
    skill7 VARCHAR(50),
    skill8 VARCHAR(50),
    other_skills TEXT,
    status ENUM('New', 'Current', 'Final') DEFAULT 'New',
    submitted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_reference) REFERENCES jobs(job_reference),
    INDEX idx_job_ref (job_reference),
    INDEX idx_status (status),
    INDEX idx_name (first_name, last_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 3. Managers Table - Store HR manager accounts
-- =====================================================
DROP TABLE IF EXISTS managers;
CREATE TABLE managers (
    manager_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100),
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    login_attempts INT DEFAULT 0,
    account_locked BOOLEAN DEFAULT FALSE,
    locked_until TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin account (password: Admin@123)
INSERT INTO managers (username, password, email, full_name) VALUES
('admin', '$2y$10$YourHashedPasswordHere', 'admin@techhive.com', 'System Administrator'),
('hr_manager', '$2y$10$YourHashedPasswordHere', 'hr@techhive.com', 'HR Manager');

-- Note: In production, use PHP's password_hash() to create secure passwords
-- Example: password_hash('Admin@123', PASSWORD_DEFAULT)

-- =====================================================
-- 4. Sample EOI Data for Testing
-- =====================================================
INSERT INTO eoi (job_reference, first_name, last_name, date_of_birth, gender, street_address, suburb, state, postcode, email, phone, skill1, skill2, skill3, other_skills, status) VALUES
('TVFED01', 'John', 'Smith', '1995-03-15', 'male', '123 Main St', 'Melbourne', 'VIC', '3000', 'john.smith@email.com', '0412345678', 'HTML5', 'CSS3', 'JavaScript', 'React experience', 'New'),
('TVFED01', 'Sarah', 'Johnson', '1992-07-22', 'female', '456 Queen St', 'Sydney', 'NSW', '2000', 'sarah.j@email.com', '0423456789', 'HTML5', 'Vue.js', 'TypeScript', 'Full-stack development', 'Current'),
('TVUXD01', 'Mike', 'Wilson', '1990-11-08', 'male', '789 King St', 'Brisbane', 'QLD', '4000', 'mike.w@email.com', '0434567890', 'Figma', 'User Research', 'Prototyping', 'Adobe Creative Suite', 'New');

-- =====================================================
-- Display table structure for verification
-- =====================================================
-- SHOW TABLES;
-- DESCRIBE jobs;
-- DESCRIBE eoi;
-- DESCRIBE managers;
