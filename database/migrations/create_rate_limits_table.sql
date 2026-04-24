-- Create rate_limits table for rate limiting
CREATE TABLE IF NOT EXISTS rate_limits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifier VARCHAR(255) NOT NULL COMMENT 'IP address or user ID',
    endpoint VARCHAR(255) NOT NULL COMMENT 'Route endpoint',
    request_count INT DEFAULT 1 COMMENT 'Number of requests in window',
    window_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Start of time window',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_identifier_endpoint (identifier, endpoint),
    INDEX idx_window_start (window_start)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
