-- Migration: Add 'Marked as changed' tracking columns to portfolio table
-- Author: Antigravity Assistant
-- Date: 2026-10-29

ALTER TABLE `portfolio`
    ADD COLUMN `is_changed` TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN `changed_note` TEXT NULL,
    ADD COLUMN `changed_at` DATETIME NULL,
    ADD COLUMN `changed_by` INT NULL,
    ADD INDEX `idx_portfolio_is_changed` (`is_changed`);

