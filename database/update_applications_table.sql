-- Update applications table to support the enhanced application form

USE studenthub;

-- Add missing columns to applications table if they don't exist
ALTER TABLE applications 
ADD COLUMN IF NOT EXISTS platform VARCHAR(100) NULL AFTER company_or_org,
ADD COLUMN IF NOT EXISTS application_link VARCHAR(500) NULL AFTER platform,
ADD COLUMN IF NOT EXISTS notes TEXT NULL AFTER application_link;