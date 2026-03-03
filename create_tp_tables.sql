-- SQL script to create database and TP tables for XAMPP
CREATE DATABASE IF NOT EXISTS `trials` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `trials`;

-- User table (Yii2 advanced migration)
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL UNIQUE,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL UNIQUE,
  `email` varchar(255) NOT NULL UNIQUE,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TP schema
CREATE TABLE IF NOT EXISTS `tp_student` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `registration_number` VARCHAR(50) NOT NULL UNIQUE,
  `names` VARCHAR(255) NOT NULL,
  `school` VARCHAR(255) NOT NULL,
  `zone` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(100) NOT NULL,
  `pathway` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tp_lecturer` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `tp_code` VARCHAR(100) NOT NULL UNIQUE,
  `telephone` VARCHAR(50) NULL,
  `payroll_number` VARCHAR(100) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tp_rubric_area` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `tp_rubric_area` (`id`,`name`,`description`) VALUES
(1,'Professional Records','Maintains accurate lesson plans and logs.'),
(2,'Lesson Planning','Develops clear and achievable lesson objectives.'),
(3,'Introduction','Engages learners at the start of the lesson.'),
(4,'Content Knowledge','Demonstrates strong subject expertise.'),
(5,'Pedagogical Strategies','Uses appropriate teaching methods.'),
(6,'Instructional Resources','Incorporates diverse materials effectively.'),
(7,'Assessment','Evaluates student learning continuously.'),
(8,'Classroom Management','Maintains a positive learning environment.'),
(9,'Closure','Summarizes and concludes lessons clearly.'),
(10,'Professionalism','Exhibits ethical and professional conduct.'),
(11,'Learner Engagement','Encourages active participation from students.'),
(12,'Inclusivity and Differentiation','Adapts teaching to diverse needs.');

CREATE TABLE IF NOT EXISTS `tp_assessment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `student_id` INT NOT NULL,
  `lecturer_id` INT NOT NULL,
  `assessment_date` DATE NOT NULL,
  `status` VARCHAR(20) NOT NULL,
  `total_score` INT DEFAULT NULL,
  `overall_performance` VARCHAR(10) DEFAULT NULL,
  `created_at` INT NOT NULL,
  `updated_at` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_tp_assessment_student` (`student_id`),
  INDEX `idx_tp_assessment_lecturer` (`lecturer_id`),
  CONSTRAINT `fk_tp_assessment_student` FOREIGN KEY (`student_id`) REFERENCES `tp_student`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tp_assessment_lecturer` FOREIGN KEY (`lecturer_id`) REFERENCES `tp_lecturer`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tp_assessment_score` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `assessment_id` INT NOT NULL,
  `rubric_area_id` INT NOT NULL,
  `score` INT NOT NULL,
  `attainment_level` VARCHAR(10) NOT NULL,
  `remark` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_assess_area` (`assessment_id`,`rubric_area_id`),
  INDEX `idx_score_assessment` (`assessment_id`),
  INDEX `idx_score_rubric` (`rubric_area_id`),
  CONSTRAINT `fk_score_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `tp_assessment`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_score_rubric` FOREIGN KEY (`rubric_area_id`) REFERENCES `tp_rubric_area`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tp_supporting_image` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `assessment_id` INT NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_support_assessment` (`assessment_id`),
  CONSTRAINT `fk_support_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `tp_assessment`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tp_report` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `assessment_id` INT NOT NULL,
  `report_type` VARCHAR(20) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `created_at` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_report_assessment` (`assessment_id`),
  CONSTRAINT `fk_report_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `tp_assessment`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tp_audit_log` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `action` VARCHAR(100) NOT NULL,
  `entity` VARCHAR(100) NOT NULL,
  `entity_id` INT NULL,
  `details` TEXT NULL,
  `created_at` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_audit_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tp_notification` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `assessment_id` INT NULL,
  `is_read` BOOLEAN NOT NULL DEFAULT FALSE,
  `created_at` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_notification_user` (`user_id`),
  CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- RBAC tables (simplified version)
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` longblob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` longblob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `idx_auth_item_child_child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- sample user entries were provided earlier and can be inserted separately
