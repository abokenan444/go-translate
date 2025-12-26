CREATE TABLE IF NOT EXISTS `cts_certificates` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `certificate_id` VARCHAR(255) UNIQUE NOT NULL,
  `document_id` BIGINT UNSIGNED,
  `translation_id` BIGINT UNSIGNED,
  `issuer_name` VARCHAR(255) NOT NULL,
  `issuer_id` VARCHAR(255),
  `status` VARCHAR(50) NOT NULL DEFAULT 'active',
  `issued_at` TIMESTAMP NOT NULL,
  `expires_at` TIMESTAMP NULL,
  `metadata` JSON,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  INDEX `idx_certificate_id` (`certificate_id`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
