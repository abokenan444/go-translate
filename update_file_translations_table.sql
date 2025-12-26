-- تحديث جدول ترجمة الملفات
-- Update file_translations table

USE cultural_translate;

-- إضافة الأعمدة الناقصة
-- Add missing columns

-- اسم الملف الأصلي
ALTER TABLE file_translations 
ADD COLUMN IF NOT EXISTS original_filename VARCHAR(255) AFTER user_id;

-- نوع الملف
ALTER TABLE file_translations 
ADD COLUMN IF NOT EXISTS file_type VARCHAR(50) AFTER original_filename;

-- مسار الملف
ALTER TABLE file_translations 
MODIFY COLUMN original_file_path TEXT AFTER file_type;

-- حجم الملف
ALTER TABLE file_translations 
ADD COLUMN IF NOT EXISTS file_size BIGINT AFTER original_file_path;

-- اللغة المصدر
ALTER TABLE file_translations 
MODIFY COLUMN source_lang VARCHAR(10) AFTER file_size;

-- اللغة الهدف  
ALTER TABLE file_translations 
MODIFY COLUMN target_lang VARCHAR(10) AFTER source_lang;

-- الحفاظ على التنسيق
ALTER TABLE file_translations 
ADD COLUMN IF NOT EXISTS preserve_layout BOOLEAN DEFAULT 1 AFTER target_lang;

-- التكيف الثقافي
ALTER TABLE file_translations 
ADD COLUMN IF NOT EXISTS cultural_adaptation BOOLEAN DEFAULT 1 AFTER preserve_layout;

-- رسالة الخطأ
ALTER TABLE file_translations 
ADD COLUMN IF NOT EXISTS error_message TEXT AFTER translated_file_path;

-- وقت الاكتمال
ALTER TABLE file_translations 
ADD COLUMN IF NOT EXISTS completed_at TIMESTAMP NULL AFTER error_message;

-- عدد الصفحات
ALTER TABLE file_translations 
ADD COLUMN IF NOT EXISTS pages_count INT DEFAULT 0 AFTER status;

-- تحديث أنواع البيانات
-- Update data types
ALTER TABLE file_translations 
MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending';

ALTER TABLE file_translations 
MODIFY COLUMN cost DECIMAL(10,2) DEFAULT 0;

-- إضافة indexes لتحسين الأداء
-- Add indexes for performance
CREATE INDEX IF NOT EXISTS idx_user_status ON file_translations(user_id, status);
CREATE INDEX IF NOT EXISTS idx_created_at ON file_translations(created_at);

-- عرض الجدول المحدث
-- Show updated table
DESCRIBE file_translations;
