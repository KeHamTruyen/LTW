-- Migration: add avatar_url and last_login to users table if not exists
ALTER TABLE users ADD COLUMN IF NOT EXISTS avatar_url VARCHAR(255) NULL AFTER phone;
ALTER TABLE users ADD COLUMN IF NOT EXISTS last_login DATETIME NULL AFTER updated_at;