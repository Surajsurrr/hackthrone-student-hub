-- Add logo field to colleges table if it doesn't exist
ALTER TABLE colleges 
ADD COLUMN IF NOT EXISTS logo VARCHAR(500) NULL AFTER website;

-- Update the college posts migration to ensure foreign key works
-- (Already exists, just for reference)
