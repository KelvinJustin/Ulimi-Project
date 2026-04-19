-- Add is_read column to messages table to track read status
ALTER TABLE messages ADD COLUMN is_read TINYINT(1) DEFAULT 0 AFTER image_path;

-- Add index on is_read for faster queries
ALTER TABLE messages ADD INDEX idx_messages_is_read (is_read);
