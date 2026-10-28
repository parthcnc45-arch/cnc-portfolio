-- Run once in phpMyAdmin against the cnc-portfolio database.
-- Existing records default to Unknown; newly recorded logins will include city.
ALTER TABLE `login_logs`
    ADD COLUMN `city` VARCHAR(120) NOT NULL DEFAULT 'Unknown' AFTER `country`;
