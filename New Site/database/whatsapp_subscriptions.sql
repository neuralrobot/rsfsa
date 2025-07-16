-- Road Safety Foundation WhatsApp Subscriptions Database Schema
-- POPIA Compliant Database Structure

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS road_safety_db 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE road_safety_db;

-- Main subscriptions table
CREATE TABLE IF NOT EXISTS whatsapp_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    whatsapp_number VARCHAR(20) NOT NULL UNIQUE,
    favorite_route ENUM('N1', 'N2', 'N3', 'N4', 'N12') NOT NULL,
    popia_consent BOOLEAN NOT NULL DEFAULT FALSE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    status ENUM('active', 'inactive', 'unsubscribed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL,
    
    INDEX idx_whatsapp_number (whatsapp_number),
    INDEX idx_favorite_route (favorite_route),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Subscription logs for audit trail (POPIA requirement)
CREATE TABLE IF NOT EXISTS subscription_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subscription_id INT,
    action ENUM('created', 'updated', 'unsubscribed', 'reactivated') NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (subscription_id) REFERENCES whatsapp_subscriptions(id) ON DELETE CASCADE,
    INDEX idx_subscription_id (subscription_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Message delivery logs
CREATE TABLE IF NOT EXISTS message_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subscription_id INT,
    message_type ENUM('welcome', 'traffic_alert', 'weather_alert', 'safety_tip', 'unsubscribe_confirmation') NOT NULL,
    message_content TEXT,
    whatsapp_message_id VARCHAR(100),
    delivery_status ENUM('sent', 'delivered', 'read', 'failed') DEFAULT 'sent',
    route_affected VARCHAR(10),
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    delivered_at TIMESTAMP NULL,
    read_at TIMESTAMP NULL,
    
    FOREIGN KEY (subscription_id) REFERENCES whatsapp_subscriptions(id) ON DELETE CASCADE,
    INDEX idx_subscription_id (subscription_id),
    INDEX idx_message_type (message_type),
    INDEX idx_delivery_status (delivery_status),
    INDEX idx_sent_at (sent_at),
    INDEX idx_route_affected (route_affected)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Traffic incidents table (for generating alerts)
CREATE TABLE IF NOT EXISTS traffic_incidents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route VARCHAR(10) NOT NULL,
    incident_type ENUM('accident', 'roadworks', 'weather', 'closure', 'congestion', 'other') NOT NULL,
    severity ENUM('low', 'medium', 'high', 'critical') NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    location VARCHAR(200),
    coordinates_lat DECIMAL(10, 8),
    coordinates_lng DECIMAL(11, 8),
    start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estimated_end_time TIMESTAMP NULL,
    actual_end_time TIMESTAMP NULL,
    status ENUM('active', 'resolved', 'monitoring') DEFAULT 'active',
    created_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_route (route),
    INDEX idx_incident_type (incident_type),
    INDEX idx_severity (severity),
    INDEX idx_status (status),
    INDEX idx_start_time (start_time),
    INDEX idx_location (location)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- POPIA data retention settings
CREATE TABLE IF NOT EXISTS data_retention_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(100) NOT NULL,
    retention_period_months INT NOT NULL DEFAULT 24,
    auto_delete BOOLEAN DEFAULT FALSE,
    last_cleanup TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_table (table_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default retention settings
INSERT INTO data_retention_settings (table_name, retention_period_months, auto_delete) VALUES
('whatsapp_subscriptions', 24, FALSE),
('subscription_logs', 12, TRUE),
('message_logs', 6, TRUE)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Create views for reporting (POPIA compliant - no personal data)
CREATE OR REPLACE VIEW subscription_stats AS
SELECT 
    favorite_route,
    COUNT(*) as total_subscriptions,
    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_subscriptions,
    COUNT(CASE WHEN status = 'unsubscribed' THEN 1 END) as unsubscribed,
    DATE(created_at) as signup_date
FROM whatsapp_subscriptions 
GROUP BY favorite_route, DATE(created_at);

CREATE OR REPLACE VIEW daily_signups AS
SELECT 
    DATE(created_at) as signup_date,
    COUNT(*) as new_subscriptions,
    COUNT(CASE WHEN favorite_route = 'N1' THEN 1 END) as n1_signups,
    COUNT(CASE WHEN favorite_route = 'N2' THEN 1 END) as n2_signups,
    COUNT(CASE WHEN favorite_route = 'N3' THEN 1 END) as n3_signups,
    COUNT(CASE WHEN favorite_route = 'N4' THEN 1 END) as n4_signups,
    COUNT(CASE WHEN favorite_route = 'N12' THEN 1 END) as n12_signups
FROM whatsapp_subscriptions 
GROUP BY DATE(created_at)
ORDER BY signup_date DESC;

-- Stored procedure for POPIA compliant data cleanup
DELIMITER //
CREATE PROCEDURE CleanupOldData()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE table_name VARCHAR(100);
    DECLARE retention_months INT;
    DECLARE auto_delete_flag BOOLEAN;
    
    DECLARE cleanup_cursor CURSOR FOR 
        SELECT table_name, retention_period_months, auto_delete 
        FROM data_retention_settings 
        WHERE auto_delete = TRUE;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cleanup_cursor;
    
    cleanup_loop: LOOP
        FETCH cleanup_cursor INTO table_name, retention_months, auto_delete_flag;
        IF done THEN
            LEAVE cleanup_loop;
        END IF;
        
        -- Clean up subscription_logs
        IF table_name = 'subscription_logs' THEN
            DELETE FROM subscription_logs 
            WHERE created_at < DATE_SUB(NOW(), INTERVAL retention_months MONTH);
        END IF;
        
        -- Clean up message_logs
        IF table_name = 'message_logs' THEN
            DELETE FROM message_logs 
            WHERE sent_at < DATE_SUB(NOW(), INTERVAL retention_months MONTH);
        END IF;
        
        -- Update last cleanup time
        UPDATE data_retention_settings 
        SET last_cleanup = NOW() 
        WHERE table_name = table_name;
        
    END LOOP;
    
    CLOSE cleanup_cursor;
END //
DELIMITER ;

-- Create event scheduler for automatic cleanup (runs monthly)
SET GLOBAL event_scheduler = ON;

CREATE EVENT IF NOT EXISTS monthly_data_cleanup
ON SCHEDULE EVERY 1 MONTH
STARTS CURRENT_TIMESTAMP
DO
    CALL CleanupOldData();

-- Sample data for testing (remove in production)
INSERT INTO whatsapp_subscriptions 
(first_name, last_name, whatsapp_number, favorite_route, popia_consent, ip_address, status) 
VALUES 
('John', 'Doe', '+27821234567', 'N3', TRUE, '192.168.1.1', 'active'),
('Jane', 'Smith', '+27829876543', 'N1', TRUE, '192.168.1.2', 'active'),
('Mike', 'Johnson', '+27834567890', 'N4', TRUE, '192.168.1.3', 'active');

-- Grant permissions (adjust as needed)
-- CREATE USER 'whatsapp_user'@'localhost' IDENTIFIED BY 'secure_password';
-- GRANT SELECT, INSERT, UPDATE ON road_safety_db.whatsapp_subscriptions TO 'whatsapp_user'@'localhost';
-- GRANT SELECT, INSERT ON road_safety_db.subscription_logs TO 'whatsapp_user'@'localhost';
-- GRANT SELECT, INSERT ON road_safety_db.message_logs TO 'whatsapp_user'@'localhost';
-- FLUSH PRIVILEGES;
