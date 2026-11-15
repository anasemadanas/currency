-- Smart Currency Converter database.
-- Re-runnable: drops and recreates the tables (all data is lost).

CREATE DATABASE IF NOT EXISTS smart_currency CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smart_currency;

DROP TABLE IF EXISTS conversions;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(128) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    reset_token_hash CHAR(64) NULL,
    reset_expires DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE conversions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    from_currency CHAR(3) NOT NULL,
    to_currency CHAR(3) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    rate DECIMAL(18,6) NOT NULL,
    converted_amount DECIMAL(18,2) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at),
    CONSTRAINT fk_conversions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Default admin / admin12345. Change it after first login (Forgot password).
INSERT INTO users (username, email, password_hash, role)
VALUES ('admin', 'admin@example.com', '$2y$10$WjnrjUiQjEIMhK7DaOcQW.A32/HJ37r1T.UlyUI31Sx1f.Qeni4yi', 'admin');
