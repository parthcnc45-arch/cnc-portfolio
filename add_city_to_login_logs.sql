-- Run once in phpMyAdmin against the cnc-portfolio database.
-- Existing records will show Unknown until their city can be backfilled.
ALTER TABLE `login_logs`
    ADD COLUMN `city` VARCHAR(120) NOT NULL DEFAULT 'Unknown' AFTER `country`;
