ALTER TABLE posts MODIFY content VARCHAR(255) NOT NULL;
ALTER TABLE posts ADD INDEX content(content);