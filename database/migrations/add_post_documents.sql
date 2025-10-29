-- Add document attachment fields to college_posts table
ALTER TABLE college_posts 
ADD COLUMN IF NOT EXISTS document_url VARCHAR(500) NULL AFTER image_url,
ADD COLUMN IF NOT EXISTS document_name VARCHAR(255) NULL AFTER document_url;
