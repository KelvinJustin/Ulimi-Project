-- Add image_path column to messages table for image attachments
ALTER TABLE messages ADD COLUMN image_path VARCHAR(255) NULL AFTER message_text;
