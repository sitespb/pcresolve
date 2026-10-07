-- PC Resolve — esquema MySQL 5.7+/8.x (também compatível com MariaDB 10.3+)
-- Executado automaticamente por: php bin/install.php

CREATE TABLE IF NOT EXISTS `settings` (
  `setting_key` VARCHAR(64) NOT NULL,
  `setting_value` TEXT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `services` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(160) NOT NULL,
  `title` VARCHAR(190) NOT NULL,
  `short_desc` TEXT NOT NULL,
  `full_desc` TEXT NOT NULL,
  `category` VARCHAR(20) NOT NULL DEFAULT 'hardware',
  `price_starting_at` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `turnaround_time` VARCHAR(100) NOT NULL DEFAULT '',
  `warranty_days` SMALLINT UNSIGNED NOT NULL DEFAULT 90,
  `icon_name` VARCHAR(40) NOT NULL DEFAULT 'Wrench',
  `image` VARCHAR(500) NOT NULL DEFAULT '',
  `highlights` TEXT NULL,
  `recommended_for` TEXT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_services_slug` (`slug`),
  KEY `idx_services_active_order` (`active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `leads` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `protocol` VARCHAR(20) NOT NULL,
  `customer_name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(40) NOT NULL,
  `email` VARCHAR(190) NULL,
  `city` VARCHAR(150) NOT NULL DEFAULT '',
  `device_type` VARCHAR(20) NOT NULL DEFAULT 'notebook',
  `service_type` VARCHAR(190) NOT NULL DEFAULT '',
  `description` TEXT NULL,
  `status` VARCHAR(30) NOT NULL DEFAULT 'pendente',
  `estimated_budget` DECIMAL(10,2) NULL,
  `final_budget` DECIMAL(10,2) NULL,
  `internal_notes` TEXT NULL,
  `source` VARCHAR(20) NOT NULL DEFAULT 'site',
  `ip_address` VARCHAR(45) NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_leads_protocol` (`protocol`),
  KEY `idx_leads_status` (`status`),
  KEY `idx_leads_created` (`created_at`),
  KEY `idx_leads_ip_created` (`ip_address`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `author` VARCHAR(150) NOT NULL,
  `location` VARCHAR(150) NOT NULL DEFAULT '',
  `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `text` TEXT NOT NULL,
  `service_title` VARCHAR(190) NOT NULL DEFAULT '',
  `testimonial_date` DATE NOT NULL,
  `verified` TINYINT(1) NOT NULL DEFAULT 1,
  `approved` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_testimonials_approved` (`approved`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `faqs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  `category` VARCHAR(80) NOT NULL DEFAULT 'Geral',
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cities` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `coverage` TEXT NOT NULL,
  `delivery_available` TINYINT(1) NOT NULL DEFAULT 1,
  `delivery_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `notes` TEXT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `phone` VARCHAR(40) NOT NULL DEFAULT '',
  `role` VARCHAR(20) NOT NULL DEFAULT 'superadmin',
  `avatar` VARCHAR(500) NOT NULL DEFAULT '',
  `department` VARCHAR(150) NOT NULL DEFAULT '',
  `password_hash` VARCHAR(255) NOT NULL,
  `notify_email_new_lead` TINYINT(1) NOT NULL DEFAULT 1,
  `notify_whatsapp_alerts` TINYINT(1) NOT NULL DEFAULT 1,
  `notify_browser_sound` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_logins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255) NOT NULL DEFAULT '',
  `session_hash` CHAR(64) NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_logins_user` (`user_id`, `id`),
  CONSTRAINT `fk_user_logins_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `email` VARCHAR(190) NOT NULL DEFAULT '',
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_login_attempts_ip` (`ip_address`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `analytics_snapshots` (
  `timeframe` VARCHAR(10) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`timeframe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
