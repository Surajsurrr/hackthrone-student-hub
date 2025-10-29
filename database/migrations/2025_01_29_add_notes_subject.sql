-- Add subject field to notes table for better categorization
-- Run this migration to update the notes table structure

ALTER TABLE notes ADD COLUMN subject VARCHAR(100) DEFAULT 'General' AFTER description;
ALTER TABLE notes ADD COLUMN likes INT DEFAULT 0 AFTER downloads;

-- Update existing records to have a default subject
UPDATE notes SET subject = 'General' WHERE subject IS NULL;

-- Add index for better performance
CREATE INDEX idx_notes_subject ON notes(subject);