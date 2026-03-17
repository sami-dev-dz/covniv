-- Create auth_logs table
CREATE TABLE IF NOT EXISTS `auth_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `num_carte` VARCHAR(20) NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `action` VARCHAR(50) NOT NULL COMMENT 'login_success, login_failed, logout, registration',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create remember_tokens table
CREATE TABLE IF NOT EXISTS `remember_tokens` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `token_hash` VARCHAR(255) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create rate_limits table
CREATE TABLE IF NOT EXISTS `rate_limits` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ip_address` VARCHAR(45) NOT NULL,
    `action_type` VARCHAR(50) NOT NULL COMMENT 'e.g., login, register',
    `attempts` INT DEFAULT 1,
    `last_attempt_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `locked_until` DATETIME NULL,
    UNIQUE KEY `ip_action` (`ip_address`, `action_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
