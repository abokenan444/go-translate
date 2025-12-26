-- تحديث جدول ترجمة الملفات
-- Update file_translations table

USE cultural_translate;

-- Drop and recreate table with correct structure
DROP TABLE IF EXISTS file_translations;

CREATE TABLE file_translations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    original_file_path TEXT NOT NULL,
    file_size BIGINT NOT NULL,
    source_lang VARCHAR(10) NOT NULL,
    target_lang VARCHAR(10) NOT NULL,
    preserve_layout BOOLEAN DEFAULT 1,
    cultural_adaptation BOOLEAN DEFAULT 1,
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    pages_count INT DEFAULT 0,
    word_count BIGINT DEFAULT 0,
    translated_file_path TEXT NULL,
    error_message TEXT NULL,
    completed_at TIMESTAMP NULL,
    cost DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_user_id (user_id),
    INDEX idx_user_status (user_id, status),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Show table structure
DESCRIBE file_translations;
