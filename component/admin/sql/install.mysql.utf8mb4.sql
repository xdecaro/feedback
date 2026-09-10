CREATE TABLE IF NOT EXISTS `#__xdecarofeedback_templates` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `alias` VARCHAR(255) NOT NULL DEFAULT '',
  `description` TEXT NULL,
  `state` TINYINT NOT NULL DEFAULT 1,
  `ordering` INT NOT NULL DEFAULT 0,
  `language` CHAR(7) NOT NULL DEFAULT '*',
  `created` DATETIME NULL,
  `created_by` INT UNSIGNED NOT NULL DEFAULT 0,
  `modified` DATETIME NULL,
  `modified_by` INT UNSIGNED NOT NULL DEFAULT 0,
  `params` TEXT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__xdecarofeedback_questions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `prompt` TEXT NOT NULL,
  `question_type` VARCHAR(50) NOT NULL,
  `options_json` MEDIUMTEXT NULL,
  `settings_json` MEDIUMTEXT NULL,
  `category` VARCHAR(100) NOT NULL DEFAULT '',
  `state` TINYINT NOT NULL DEFAULT 1,
  `ordering` INT NOT NULL DEFAULT 0,
  `language` CHAR(7) NOT NULL DEFAULT '*',
  `created` DATETIME NULL,
  `created_by` INT UNSIGNED NOT NULL DEFAULT 0,
  `modified` DATETIME NULL,
  `modified_by` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`question_type`),
  KEY `idx_state` (`state`),
  KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__xdecarofeedback_template_questions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `template_id` INT UNSIGNED NOT NULL,
  `source_question_id` INT UNSIGNED NULL,
  `prompt` TEXT NOT NULL,
  `question_type` VARCHAR(50) NOT NULL,
  `options_json` MEDIUMTEXT NULL,
  `settings_json` MEDIUMTEXT NULL,
  `required` TINYINT(1) NOT NULL DEFAULT 0,
  `ordering` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_template` (`template_id`),
  KEY `idx_source_question` (`source_question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__xdecarofeedback_questionnaires` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `alias` VARCHAR(255) NOT NULL DEFAULT '',
  `description` TEXT NULL,
  `template_id` INT UNSIGNED NULL,
  `state` TINYINT NOT NULL DEFAULT 1,
  `access` INT UNSIGNED NOT NULL DEFAULT 1,
  `anonymity_mode` VARCHAR(20) NOT NULL DEFAULT 'anonymous',
  `response_policy` VARCHAR(20) NOT NULL DEFAULT 'multiple',
  `starts_at` DATETIME NULL,
  `ends_at` DATETIME NULL,
  `target_extension` VARCHAR(100) NOT NULL DEFAULT '',
  `target_type` VARCHAR(100) NOT NULL DEFAULT '',
  `target_identifier` VARCHAR(255) NOT NULL DEFAULT '',
  `language` CHAR(7) NOT NULL DEFAULT '*',
  `ordering` INT NOT NULL DEFAULT 0,
  `created` DATETIME NULL,
  `created_by` INT UNSIGNED NOT NULL DEFAULT 0,
  `modified` DATETIME NULL,
  `modified_by` INT UNSIGNED NOT NULL DEFAULT 0,
  `params` TEXT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_access` (`access`),
  KEY `idx_period` (`starts_at`, `ends_at`),
  KEY `idx_target` (`target_extension`, `target_type`, `target_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__xdecarofeedback_questionnaire_questions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `questionnaire_id` INT UNSIGNED NOT NULL,
  `source_question_id` INT UNSIGNED NULL,
  `prompt` TEXT NOT NULL,
  `question_type` VARCHAR(50) NOT NULL,
  `options_json` MEDIUMTEXT NULL,
  `settings_json` MEDIUMTEXT NULL,
  `required` TINYINT(1) NOT NULL DEFAULT 0,
  `ordering` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_questionnaire` (`questionnaire_id`),
  KEY `idx_source_question` (`source_question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__xdecarofeedback_submissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `public_id` CHAR(36) NOT NULL,
  `questionnaire_id` INT UNSIGNED NOT NULL,
  `respondent_user_id` INT UNSIGNED NULL,
  `respondent_key_hash` CHAR(64) NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'submitted',
  `started_at` DATETIME NULL,
  `submitted_at` DATETIME NULL,
  `language` CHAR(7) NOT NULL DEFAULT '*',
  `metadata_json` MEDIUMTEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_public_id` (`public_id`),
  UNIQUE KEY `uq_questionnaire_respondent` (`questionnaire_id`, `respondent_key_hash`),
  KEY `idx_questionnaire_status` (`questionnaire_id`, `status`),
  KEY `idx_user` (`respondent_user_id`),
  KEY `idx_submitted` (`submitted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__xdecarofeedback_answers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `submission_id` BIGINT UNSIGNED NOT NULL,
  `questionnaire_question_id` INT UNSIGNED NOT NULL,
  `value_text` MEDIUMTEXT NULL,
  `value_json` MEDIUMTEXT NULL,
  `numeric_value` DECIMAL(12,4) NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_submission_question` (`submission_id`, `questionnaire_question_id`),
  KEY `idx_question` (`questionnaire_question_id`),
  KEY `idx_numeric` (`numeric_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
