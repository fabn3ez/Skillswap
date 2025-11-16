-- =============================================
-- SkillSwap Database Setup
-- Advanced Database Systems Project
-- =============================================

CREATE DATABASE IF NOT EXISTS skillswap;
USE skillswap;

-- =============================================
-- Core User Tables
-- =============================================

CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    user_type ENUM('freelancer', 'client', 'admin', 'moderator') NOT NULL,
    profile_picture VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE freelancers (
    freelancer_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    tagline VARCHAR(200),
    bio TEXT,
    hourly_rate DECIMAL(10,2),
    total_earnings DECIMAL(12,2) DEFAULT 0,
    avg_rating DECIMAL(3,2) DEFAULT 0,
    total_projects INT DEFAULT 0,
    success_rate DECIMAL(5,2) DEFAULT 0,
    location VARCHAR(100),
    website VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE clients (
    client_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    company_name VARCHAR(100) NOT NULL,
    company_size ENUM('startup', 'small', 'medium', 'large', 'enterprise'),
    industry VARCHAR(100),
    website VARCHAR(255),
    total_spent DECIMAL(12,2) DEFAULT 0,
    total_projects_posted INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =============================================
-- Skills & Categories
-- =============================================

CREATE TABLE skill_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    parent_category_id INT NULL,
    FOREIGN KEY (parent_category_id) REFERENCES skill_categories(category_id)
);

CREATE TABLE skills (
    skill_id INT PRIMARY KEY AUTO_INCREMENT,
    skill_name VARCHAR(50) UNIQUE NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    demand_level ENUM('low', 'medium', 'high', 'very_high') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES skill_categories(category_id)
);

CREATE TABLE freelancer_skills (
    freelancer_skill_id INT PRIMARY KEY AUTO_INCREMENT,
    freelancer_id INT NOT NULL,
    skill_id INT NOT NULL,
    proficiency_level ENUM('beginner', 'intermediate', 'advanced', 'expert') NOT NULL,
    years_of_experience INT DEFAULT 0,
    is_verified BOOLEAN DEFAULT FALSE,
    verified_by INT NULL,
    verified_at TIMESTAMP NULL,
    verification_hash VARCHAR(255), -- For blockchain verification
    FOREIGN KEY (freelancer_id) REFERENCES freelancers(freelancer_id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(skill_id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(user_id),
    UNIQUE KEY unique_freelancer_skill (freelancer_id, skill_id)
);

-- =============================================
-- Projects & Bidding System
-- =============================================

CREATE TABLE projects (
    project_id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    project_type ENUM('fixed', 'hourly') NOT NULL,
    budget_low DECIMAL(10,2) NOT NULL,
    budget_high DECIMAL(10,2) NOT NULL,
    deadline DATE NOT NULL,
    status ENUM('draft', 'posted', 'in_progress', 'completed', 'cancelled') DEFAULT 'draft',
    ai_matching_enabled BOOLEAN DEFAULT TRUE,
    escrow_required BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE
);

CREATE TABLE project_skills (
    project_skill_id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    skill_id INT NOT NULL,
    importance_level ENUM('required', 'preferred', 'optional') DEFAULT 'required',
    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(skill_id) ON DELETE CASCADE,
    UNIQUE KEY unique_project_skill (project_id, skill_id)
);

CREATE TABLE bids (
    bid_id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    bid_amount DECIMAL(10,2) NOT NULL,
    proposal_text TEXT NOT NULL,
    estimated_days INT NOT NULL,
    bid_status ENUM('submitted', 'under_review', 'accepted', 'rejected') DEFAULT 'submitted',
    match_percentage DECIMAL(5,2) DEFAULT 0, -- AI matching score
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES freelancers(freelancer_id) ON DELETE CASCADE,
    UNIQUE KEY unique_project_freelancer (project_id, freelancer_id)
);

-- =============================================
-- Contracts & Payments
-- =============================================

CREATE TABLE contracts (
    contract_id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT UNIQUE NOT NULL,
    freelancer_id INT NOT NULL,
    client_id INT NOT NULL,
    contract_amount DECIMAL(10,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    contract_status ENUM('active', 'completed', 'terminated', 'disputed') DEFAULT 'active',
    payment_method ENUM('escrow', 'direct', 'milestone') DEFAULT 'escrow',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(project_id),
    FOREIGN KEY (freelancer_id) REFERENCES freelancers(freelancer_id),
    FOREIGN KEY (client_id) REFERENCES clients(client_id)
);

CREATE TABLE milestones (
    milestone_id INT PRIMARY KEY AUTO_INCREMENT,
    contract_id INT NOT NULL,
    milestone_name VARCHAR(100) NOT NULL,
    description TEXT,
    amount DECIMAL(10,2) NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('pending', 'in_review', 'approved', 'rejected') DEFAULT 'pending',
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id) ON DELETE CASCADE
);

CREATE TABLE transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    contract_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    client_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    transaction_type ENUM('payment', 'refund', 'withdrawal') NOT NULL,
    payment_method VARCHAR(50),
    status ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    blockchain_tx_hash VARCHAR(255), -- For blockchain transactions
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id),
    FOREIGN KEY (freelancer_id) REFERENCES freelancers(freelancer_id),
    FOREIGN KEY (client_id) REFERENCES clients(client_id)
);

-- =============================================
-- Reviews & Ratings
-- =============================================

CREATE TABLE reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    contract_id INT UNIQUE NOT NULL,
    reviewer_id INT NOT NULL, -- User who wrote the review
    reviewee_id INT NOT NULL, -- User being reviewed
    rating TINYINT CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    review_type ENUM('client_to_freelancer', 'freelancer_to_client') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id),
    FOREIGN KEY (reviewer_id) REFERENCES users(user_id),
    FOREIGN KEY (reviewee_id) REFERENCES users(user_id)
);

-- =============================================
-- AI Matching & Analytics
-- =============================================

CREATE TABLE skill_matches (
    match_id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    match_percentage DECIMAL(5,2) NOT NULL,
    matching_factors JSON, -- Stores which skills matched, experience level, etc.
    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES freelancers(freelancer_id) ON DELETE CASCADE,
    UNIQUE KEY unique_project_freelancer_match (project_id, freelancer_id)
);

CREATE TABLE user_behavior_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action_type VARCHAR(50) NOT NULL, -- 'search', 'view_project', 'apply', 'message'
    target_id INT, -- Project ID, User ID, etc.
    action_data JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- =============================================
-- Sample Data Insertion
-- =============================================

-- Insert skill categories
INSERT INTO skill_categories (category_name, description) VALUES
('Web Development', 'Frontend and backend web technologies'),
('Mobile Development', 'iOS, Android, and cross-platform development'),
('Data Science', 'AI, ML, data analysis and visualization'),
('Design', 'UI/UX design, graphic design, prototyping'),
('DevOps', 'Infrastructure, cloud, and deployment');

-- Insert skills
INSERT INTO skills (skill_name, category_id, demand_level) VALUES
('React.js', 1, 'very_high'),
('Node.js', 1, 'high'),
('Python', 3, 'very_high'),
('UI/UX Design', 4, 'high'),
('AWS', 5, 'high'),
('MongoDB', 1, 'medium'),
('Swift', 2, 'medium'),
('Machine Learning', 3, 'very_high'),
('Docker', 5, 'high'),
('TypeScript', 1, 'high');

-- Insert sample users (passwords are hashed - 'password123')
INSERT INTO users (username, email, password_hash, user_type) VALUES
('john_dev', 'john@email.com', '$2b$10$examplehash1', 'freelancer'),
('sarah_design', 'sarah@email.com', '$2b$10$examplehash2', 'freelancer'),
('tech_corp', 'contact@techcorp.com', '$2b$10$examplehash3', 'client'),
('admin_skill', 'admin@skillswap.com', '$2b$10$examplehash4', 'admin');

-- Insert freelancers
INSERT INTO freelancers (user_id, full_name, tagline, hourly_rate, location) VALUES
(1, 'John Developer', 'Full-Stack JavaScript Expert', 75.00, 'New York, USA'),
(2, 'Sarah Designer', 'Creative UI/UX Designer', 60.00, 'London, UK');

-- Insert clients
INSERT INTO clients (user_id, company_name, company_size, industry) VALUES
(3, 'TechCorp Inc', 'medium', 'Technology'),
(4, 'SkillSwap Platform', 'large', 'Technology');

-- Insert freelancer skills
INSERT INTO freelancer_skills (freelancer_id, skill_id, proficiency_level, years_of_experience, is_verified) VALUES
(1, 1, 'expert', 5, TRUE),
(1, 2, 'advanced', 4, TRUE),
(1, 6, 'intermediate', 3, TRUE),
(2, 4, 'expert', 6, TRUE),
(2, 1, 'intermediate', 2, FALSE);

-- Insert sample project
INSERT INTO projects (client_id, title, description, project_type, budget_low, budget_high, deadline, status) VALUES
(1, 'E-commerce Platform Development', 'Need a full-stack developer to build a modern e-commerce platform with React and Node.js', 'fixed', 5000.00, 8000.00, '2024-03-15', 'posted');

-- Insert project skills
INSERT INTO project_skills (project_id, skill_id, importance_level) VALUES
(1, 1, 'required'),
(1, 2, 'required'),
(1, 6, 'preferred');

-- Insert sample bids
INSERT INTO bids (project_id, freelancer_id, bid_amount, proposal_text, estimated_days, match_percentage) VALUES
(1, 1, 6500.00, 'I have extensive experience building e-commerce platforms with MERN stack. I can deliver a high-quality, scalable solution within your timeline.', 21, 95.50);

ALTER TABLE projects ADD COLUMN ai_embedding JSON;


-- =============================================
-- Views for User Management
-- =============================================

CREATE VIEW freelancer_profiles AS
SELECT 
    f.freelancer_id,
    u.username,
    f.full_name,
    f.tagline,
    f.hourly_rate,
    f.avg_rating,
    f.total_projects,
    f.success_rate,
    GROUP_CONCAT(DISTINCT s.skill_name) as skills,
    COUNT(DISTINCT fs.skill_id) as total_skills
FROM freelancers f
JOIN users u ON f.user_id = u.user_id
LEFT JOIN freelancer_skills fs ON f.freelancer_id = fs.freelancer_id
LEFT JOIN skills s ON fs.skill_id = s.skill_id
WHERE u.is_active = TRUE
GROUP BY f.freelancer_id;

CREATE VIEW project_matches AS
SELECT 
    p.project_id,
    p.title,
    p.budget_high,
    b.freelancer_id,
    b.match_percentage,
    f.full_name,
    b.bid_amount
FROM projects p
JOIN bids b ON p.project_id = b.project_id
JOIN freelancers f ON b.freelancer_id = f.freelancer_id
WHERE p.status = 'posted' AND b.bid_status = 'submitted';

-- =============================================
-- Stored Procedures
-- =============================================

DELIMITER //

CREATE PROCEDURE CalculateFreelancerSuccessRate(IN freelancer_id_param INT)
BEGIN
    DECLARE total_projects INT;
    DECLARE successful_projects INT;
    DECLARE success_rate DECIMAL(5,2);
    
    SELECT COUNT(*) INTO total_projects
    FROM contracts c
    WHERE c.freelancer_id = freelancer_id_param;
    
    SELECT COUNT(*) INTO successful_projects
    FROM contracts c
    WHERE c.freelancer_id = freelancer_id_param 
    AND c.contract_status = 'completed';
    
    IF total_projects > 0 THEN
        SET success_rate = (successful_projects / total_projects) * 100;
    ELSE
        SET success_rate = 0;
    END IF;
    
    UPDATE freelancers 
    SET success_rate = success_rate, 
        total_projects = total_projects
    WHERE freelancer_id = freelancer_id_param;
END//

CREATE PROCEDURE GetTopMatchedProjects(IN freelancer_id_param INT, IN limit_count INT)
BEGIN
    SELECT 
        p.project_id,
        p.title,
        p.budget_high,
        p.deadline,
        sm.match_percentage,
        GROUP_CONCAT(s.skill_name) as required_skills
    FROM projects p
    JOIN skill_matches sm ON p.project_id = sm.project_id
    JOIN project_skills ps ON p.project_id = ps.project_id
    JOIN skills s ON ps.skill_id = s.skill_id
    WHERE sm.freelancer_id = freelancer_id_param 
    AND p.status = 'posted'
    GROUP BY p.project_id, p.title, p.budget_high, p.deadline, sm.match_percentage
    ORDER BY sm.match_percentage DESC
    LIMIT limit_count;
END//

DELIMITER ;

-- =============================================
-- Create Users and Grant Permissions
-- =============================================

CREATE USER 'skillswap_app'@'localhost' IDENTIFIED BY 'app_password123';
GRANT SELECT, INSERT, UPDATE, DELETE ON skillswap.* TO 'skillswap_app'@'localhost';

CREATE USER 'skillswap_analyst'@'localhost' IDENTIFIED BY 'analyst_password123';
GRANT SELECT ON skillswap.* TO 'skillswap_analyst'@'localhost';
GRANT EXECUTE ON PROCEDURE skillswap.GetTopMatchedProjects TO 'skillswap_analyst'@'localhost';

FLUSH PRIVILEGES;

-- =============================================
-- Final Setup Complete
-- =============================================

SELECT 'SkillSwap Database Setup Complete!' as status;