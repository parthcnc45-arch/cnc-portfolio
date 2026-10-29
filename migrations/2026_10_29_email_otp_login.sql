-- CodeandCore email OTP login setup
-- Select the application's database in phpMyAdmin before running this script.
-- This script is safe to re-run and does not overwrite existing users.

CREATE TABLE IF NOT EXISTS login_email_otps (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    otp_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    used_at DATETIME NULL DEFAULT NULL,
    request_ip VARCHAR(45) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_login_email_otps_user_id (user_id),
    KEY idx_login_email_otps_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (username, password, created_at)
SELECT 'mayur@codeandcore.com', 'OTP_ONLY_LOGIN_DISABLED', NOW()
WHERE NOT EXISTS (SELECT 1 FROM users WHERE LOWER(username) = 'mayur@codeandcore.com');

INSERT INTO users (username, password, created_at)
SELECT 'himanshu@codeandcore.com', 'OTP_ONLY_LOGIN_DISABLED', NOW()
WHERE NOT EXISTS (SELECT 1 FROM users WHERE LOWER(username) = 'himanshu@codeandcore.com');

INSERT INTO users (username, password, created_at)
SELECT 'hello@codeandcore.com', 'OTP_ONLY_LOGIN_DISABLED', NOW()
WHERE NOT EXISTS (SELECT 1 FROM users WHERE LOWER(username) = 'hello@codeandcore.com');

INSERT INTO users (username, password, created_at)
SELECT 'nikhar@codeandcore.com', 'OTP_ONLY_LOGIN_DISABLED', NOW()
WHERE NOT EXISTS (SELECT 1 FROM users WHERE LOWER(username) = 'nikhar@codeandcore.com');

INSERT INTO users (username, password, created_at)
SELECT 'nagender@codeandcore.com', 'OTP_ONLY_LOGIN_DISABLED', NOW()
WHERE NOT EXISTS (SELECT 1 FROM users WHERE LOWER(username) = 'nagender@codeandcore.com');
