ALTER TABLE comments ADD COLUMN ip VARCHAR(15) NOT NULL DEFAULT '' AFTER content;