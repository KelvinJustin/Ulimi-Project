-- Add slug column to users table for secure, non-sequential URLs
ALTER TABLE users ADD COLUMN slug VARCHAR(190) NULL UNIQUE AFTER email;
CREATE INDEX idx_users_slug ON users(slug);

-- Generate slugs for existing users based on email username
UPDATE users SET slug = SUBSTRING_INDEX(email, '@', 1) WHERE slug IS NULL;

-- Handle duplicate slugs by appending a number
SET @row_number = 0;
UPDATE users u1 
SET slug = CONCAT(SUBSTRING_INDEX(u1.email, '@', 1), '-', (@row_number := @row_number + 1))
WHERE slug IN (
    SELECT slug FROM (
        SELECT slug, COUNT(*) as cnt 
        FROM users 
        WHERE slug IS NOT NULL 
        GROUP BY slug 
        HAVING cnt > 1
    ) AS duplicates
);
