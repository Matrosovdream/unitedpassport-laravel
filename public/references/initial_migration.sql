-- Adminer 4.8.1 MySQL 8.0.42 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `easypost_shipment_addresses`;
CREATE TABLE `easypost_shipment_addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `easypost_id` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `entry_id` bigint unsigned DEFAULT NULL,
  `address_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `street1` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `street2` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `zip` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `country` varchar(2) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `easypost_shipment_id` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_easypost_id` (`easypost_id`),
  KEY `idx_entry_id` (`entry_id`),
  KEY `idx_type` (`address_type`),
  KEY `idx_zip` (`zip`),
  KEY `idx_country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `easypost_shipment_history`;
CREATE TABLE `easypost_shipment_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_id` bigint unsigned DEFAULT NULL,
  `easypost_shipment_id` varchar(64) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `change_type` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `easypost_shipment_label`;
CREATE TABLE `easypost_shipment_label` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `easypost_id` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `easypost_shipment_id` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `entry_id` bigint unsigned DEFAULT NULL,
  `date_advance` int DEFAULT '0',
  `integrated_form` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `label_date` datetime DEFAULT NULL,
  `label_resolution` int DEFAULT NULL,
  `label_size` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `label_type` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `label_file_type` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `label_url` text COLLATE utf8mb4_unicode_520_ci,
  `label_pdf_url` text COLLATE utf8mb4_unicode_520_ci,
  `label_zpl_url` text COLLATE utf8mb4_unicode_520_ci,
  `label_epl2_url` text COLLATE utf8mb4_unicode_520_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_easypost_id` (`easypost_id`),
  KEY `idx_entry_id` (`entry_id`),
  KEY `idx_label_date` (`label_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `easypost_shipment_parcel`;
CREATE TABLE `easypost_shipment_parcel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `easypost_id` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `entry_id` bigint unsigned DEFAULT NULL,
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `easypost_shipment_id` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_easypost_id` (`easypost_id`),
  KEY `idx_entry_id` (`entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `easypost_shipment_rate`;
CREATE TABLE `easypost_shipment_rate` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `easypost_id` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `entry_id` bigint unsigned DEFAULT NULL,
  `mode` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'test',
  `service` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `carrier` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `rate` decimal(12,2) DEFAULT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `retail_rate` decimal(12,2) DEFAULT NULL,
  `retail_currency` char(3) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `list_rate` decimal(12,2) DEFAULT NULL,
  `list_currency` char(3) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `billing_type` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `delivery_days` int DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `delivery_date_guaranteed` tinyint(1) DEFAULT NULL,
  `est_delivery_days` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `easypost_shipment_id` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_easypost_id` (`easypost_id`),
  KEY `idx_entry_id` (`entry_id`),
  KEY `idx_carrier` (`carrier`),
  KEY `idx_service` (`service`),
  KEY `idx_mode` (`mode`),
  KEY `idx_delivery_date` (`delivery_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `easypost_shipments`;
CREATE TABLE `easypost_shipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `easypost_id` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `entry_id` bigint unsigned DEFAULT NULL,
  `is_return` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `tracking_code` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `refund_status` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mode` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'test',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tracking_url` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_easypost_id` (`easypost_id`),
  KEY `idx_entry_id` (`entry_id`),
  KEY `idx_tracking_code` (`tracking_code`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `form_fields`;
CREATE TABLE `form_fields` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `field_key` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `name` text COLLATE utf8mb4_unicode_520_ci,
  `description` longtext COLLATE utf8mb4_unicode_520_ci,
  `type` text COLLATE utf8mb4_unicode_520_ci,
  `default_value` longtext COLLATE utf8mb4_unicode_520_ci,
  `options` longtext COLLATE utf8mb4_unicode_520_ci,
  `field_order` int DEFAULT '0',
  `required` int DEFAULT NULL,
  `field_options` longtext COLLATE utf8mb4_unicode_520_ci,
  `form_id` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `field_key` (`field_key`),
  KEY `form_id` (`form_id`),
  KEY `idx_form_id_type` (`form_id`,`type`(30))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `forms`;
CREATE TABLE `forms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `form_key` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci,
  `parent_form_id` int DEFAULT '0',
  `logged_in` tinyint(1) DEFAULT NULL,
  `editable` tinyint(1) DEFAULT NULL,
  `is_template` tinyint(1) DEFAULT '0',
  `default_template` tinyint(1) DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `options` longtext COLLATE utf8mb4_unicode_520_ci,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `form_key` (`form_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `form_item_metas`;
CREATE TABLE `form_item_metas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  `field_id` bigint NOT NULL,
  `item_id` bigint NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `field_id` (`field_id`),
  KEY `item_id` (`item_id`),
  KEY `idx_field_id_item_id` (`field_id`,`item_id`),
  KEY `idx_field_value_item` (`field_id`,`meta_value`(191),`item_id`),
  KEY `idx_field_meta191` (`field_id`,`meta_value`(191)),
  KEY `idx_item_meta191` (`item_id`,`meta_value`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `form_items`;
CREATE TABLE `form_items` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `item_key` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci,
  `ip` text COLLATE utf8mb4_unicode_520_ci,
  `form_id` bigint DEFAULT NULL,
  `post_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `parent_item_id` bigint DEFAULT '0',
  `is_draft` tinyint(1) DEFAULT '0',
  `updated_by` bigint DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_key` (`item_key`),
  KEY `form_id` (`form_id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_item_id` (`parent_item_id`),
  KEY `idx_is_draft_created_at` (`is_draft`,`created_at`),
  KEY `idx_form_id_is_draft` (`form_id`,`is_draft`),
  KEY `idx_isdraft_id` (`is_draft`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `midigator_preventions`;
CREATE TABLE `midigator_preventions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amount` decimal(12,2) DEFAULT NULL,
  `arn` varchar(64) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `card_brand` varchar(30) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `card_first_6` varchar(12) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `card_last_4` varchar(8) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `merchant_descriptor` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mid` varchar(64) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `order_guid` varchar(64) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `order_id` varchar(64) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `prevention_case_number` varchar(64) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `prevention_guid` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `prevention_timestamp` datetime DEFAULT NULL,
  `prevention_type` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `transaction_timestamp` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_resolved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_prevention_guid` (`prevention_guid`),
  KEY `idx_order_guid` (`order_guid`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_mid` (`mid`),
  KEY `idx_arn` (`arn`),
  KEY `idx_prevention_type` (`prevention_type`),
  KEY `idx_prevention_ts` (`prevention_timestamp`),
  KEY `idx_transaction_ts` (`transaction_timestamp`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_is_resolved` (`is_resolved`),
  KEY `idx_updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `midigator_rdr`;
CREATE TABLE `midigator_rdr` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `amount` decimal(12,2) DEFAULT NULL,
  `arn` varchar(64) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `auth_code` varchar(32) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `card_first_6` varchar(12) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `card_last_4` varchar(8) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `merchant_descriptor` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `event_guid` varchar(64) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `event_timestamp` datetime DEFAULT NULL,
  `event_type` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `rdr_guid` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `rdr_case_number` varchar(64) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `rdr_date` date DEFAULT NULL,
  `rdr_resolution` varchar(80) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `prevention_type` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `order_id` varchar(64) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `is_resolved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_rdr_guid` (`rdr_guid`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_arn` (`arn`),
  KEY `idx_rdr_case_number` (`rdr_case_number`),
  KEY `idx_rdr_date` (`rdr_date`),
  KEY `idx_rdr_resolution` (`rdr_resolution`),
  KEY `idx_prevention_type` (`prevention_type`),
  KEY `idx_event_timestamp` (`event_timestamp`),
  KEY `idx_transaction_date` (`transaction_date`),
  KEY `idx_is_resolved` (`is_resolved`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `midigator_resolve_history`;
CREATE TABLE `midigator_resolve_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `resolve_id` bigint unsigned NOT NULL,
  `prevention_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `prevention_guid` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `resolution_type` varchar(80) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_resolve_id` (`resolve_id`),
  KEY `idx_prevention_id` (`prevention_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_prevention_guid` (`prevention_guid`),
  KEY `idx_resolution_type` (`resolution_type`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_prevention_timeline` (`prevention_id`,`created_at`),
  KEY `idx_resolve_timeline` (`resolve_id`,`created_at`),
  KEY `idx_user_timeline` (`user_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `midigator_resolves`;
CREATE TABLE `midigator_resolves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prevention_id` bigint unsigned NOT NULL,
  `prevention_guid` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `resolution_type` varchar(80) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_prevention_id` (`prevention_id`),
  KEY `idx_prevention_guid` (`prevention_guid`),
  KEY `idx_resolution_type` (`resolution_type`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  `receipt_id` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `invoice_id` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sub_id` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `item_id` bigint NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `begin_date` date NOT NULL,
  `expire_date` date DEFAULT NULL,
  `paysys` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `test` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `payments_authnet`;
CREATE TABLE `payments_authnet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `amount` float NOT NULL,
  `payment_id` int NOT NULL,
  `invoice_id` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_id` int NOT NULL,
  `form_id` int NOT NULL,
  `authnet_login_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `authnet_transaction_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_payment_id` (`payment_id`),
  KEY `idx_form_created` (`form_id`,`created_at`),
  KEY `idx_invoice_id` (`invoice_id`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


DROP TABLE IF EXISTS `payments_failed`;
CREATE TABLE `payments_failed` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entry_id` int NOT NULL,
  `form_id` int NOT NULL,
  `payment_id` int NOT NULL,
  `error_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `error_message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_entry_id` (`entry_id`),
  KEY `idx_payment_id` (`payment_id`),
  KEY `idx_form_created` (`form_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `refunds_authnet`;
CREATE TABLE `refunds_authnet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sum` float NOT NULL,
  `payment_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_payment_id` (`payment_id`),
  KEY `idx_payment_created` (`payment_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `site_options`;
CREATE TABLE `site_options` (
  `id` int NOT NULL AUTO_INCREMENT, 
  `option_name` varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  KEY `autoload` (`autoload`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


-- 2026-03-24 10:59:36

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_status` int NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
